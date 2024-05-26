<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EstablishmentController extends BaseController
{
    // get /establishment/dashboard
    public function dashboard()
    {
        return response()->json(["role" => "establishment", "session" => session()->all()], 200); //edit to return session as well ==>> already added session
    }

    // post /establishment/create/transaction
    public function create(Request $request)
    {
        DB::beginTransaction();

        try {
            $validation = $this->checkRequest($request, $this->getStrictScope());
            if ($validation !== null) {
                throw new \Exception($validation); // Rollback the transaction
            }

            $contents = $request->input('contents');
            $productList = $contents['products'];
            $date = $contents['date'];
            $establishment_id = $contents['establishment_id'];
            $senior_username = $contents['senior_username'];

            $senior = DB::table('senior')->where('username', $senior_username)->first();
            if (!$senior) {
                throw new \Exception('Senior not found'); // Rollback the transaction
            }
            $senior_id = $senior->id;

            $transactionID = $this->createTransaction($date, $establishment_id, $senior_id);

            // Create product transactions
            foreach ($productList as $product) {
                $productID = $this->createProduct($product);
                $this->createProductTransaction($productID, $transactionID);
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Created successfully', 'id' => $transactionID], 201);
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();

            return response()->json(['error' => 'Creation failed', 'message' => $e->getMessage()], 400);
        }
    }

    private function createTransaction($date, $establishment_id, $senior_id)
    {
        try {
            $transactionID = DB::table('transaction')->insertGetId([
                'date' => $date,
                'establishment_id' => $establishment_id,
                'senior_id' => $senior_id
            ]);

            if (!$transactionID) {
                throw new \Exception("Failed to create transaction.");
            }

            return $transactionID;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function createProduct($product)
    {
        try {
            $productID = DB::table('products')->insertGetId([
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $product['quantity']
            ]);

            if (!$productID) {
                throw new \Exception("Failed to create product.");
            }

            return $productID;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function createProductTransaction($productID, $transactionID)
    {
        try {
            DB::table('product_transaction')->insert([
                'products_id' => $productID,
                'transaction_id' => $transactionID,
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // get /establishment/{establishment_username}/show/{client}
    public function read($client, $establishment_username)
    {
        $fields = '*';
        $extraClause = '';

        switch($client){
            case 'senior':
                $fields = 'senior.id, senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
                $extraClause = 'LEFT JOIN barangay 
                                ON senior.barangay_id = barangay.id 
                                WHERE  = :b_id'; // to fix this
                break;
            case 'transaction':
                //transactions with products
                break;
            default:
                return response()->json(['error' => 'Unknown client type'], 404);
        }

        if (is_null($bID)) {
            return response()->json(['error' => 'bID parameter is required'], 400);
        }

        return $this->generateReadResponse($fields, $extraClause, $client, ['sID' => $sID]);
    }

    // get /establishment/show/transaction/{senior_username}
    public function readTransaction($senior_username)
    {
        $senior = DB::table('senior')->where('username', $senior_username)->first();

        if (!$senior) {
            return response()->json(['error' => 'Senior not found'], 404);
        }

        $transactions = DB::table('transaction')
            ->where('senior_id', $senior->id)
            ->get();

        $formattedTransactions = [];
        foreach ($transactions as $transaction) {
            $establishment = DB::table('establishment')
                ->where('id', $transaction->establishment_id)
                ->first();

            $productIds = DB::table('product_transaction')
                ->where('transaction_id', $transaction->id)
                ->pluck('products_id')
                ->toArray();

            $products = DB::table('products')
                ->whereIn('id', $productIds)
                ->get();

            $formattedTransaction = [
                'id' => $transaction->id,
                'date' => $transaction->date,
                'establishment_name' => $establishment->name,
                'establishment_code' => $establishment->code,
                'products' => $products,
            ];

            $formattedTransactions[] = $formattedTransaction;
        }

        return response()->json($formattedTransactions, 200);
    }

    // post /establishment/update
    public function update(Request $request)
    {
        $validation = $this->checkRequest($request, $this->getStrictScope());

        if ($validation !== null) {
            return $validation;
        }

        $type = $request->input('type');
        $contents = $request->input('contents');

        try {
            $this->updateEntity($type, $contents);
            return response()->json(['message' => 'Update successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed', 'message' => $e->getMessage()], 400);
        }
    }

    // post /establishment/update/product
    public function updateProduct(Request $request)
    {
        //create here
    }

    private function updateEntity($table, $contents)
    {
        $rules = $this->getRules($table);

        if (empty($rules)) {
            throw new \Exception('Invalid table name or missing validation rules.');
        }

        // create validator for product and transaction

        $rules = $this->transformRulesForUpdate($rules, $contents); // check for this validaty 
        $validator = Validator::make($contents, $rules);

        if ($validator->fails()) {
            throw new \Exception($this->generateErrorMessage($validator));
        }

        if (!isset($contents['id'])) {
            throw new \Exception('ID not provided for update');
        }

        $id = $contents['id'];
        unset($contents['id']); // remove id from content to avoid update of id

        $contents = array_filter($contents, function ($value) {
            return $value !== "" && $value !== null;
        });

        $affected = DB::table($table)->where('id', $id)->update($contents);

        if ($affected === 0) {
            throw new \Exception('No record found to update or no valid changes made');
        }
    }

    // post /establishment/delete
    public function delete(Request $request)
    {
        $validation = $this->checkRequest($request, $this->getStrictScope());

        if ($validation !== null) {
            return $validation;
        }

        $type = $request->input('type');
        $contents = $request->input('contents');

        try {
            $this->deleteEntity($type, $contents);
            return response()->json(['message' => 'Deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Deletion failed', 'message' => $e->getMessage()], 400);
        }
    }

    // post establishment/delete/product
    public function deleteProduct(Request $request)
    {
        //createa a function
    }

    private function deleteEntity($table, $contents)
    {
        if (!isset($contents['id'])) {
            throw new \Exception('Missing id.');
        }

        $id = $contents['id'];

        $affectedRows = DB::table($table)->where('id', $id)->delete();

        if ($affectedRows === 0) {
            throw new \Exception('Record not found or no rows deleted.');
        }
    }

    private function getScope()
    {
        return "required|string|in:senior,transaction, products";
    }

    private function getStrictScope()
    {
        return "required|string|in:transaction, products";
    }
}