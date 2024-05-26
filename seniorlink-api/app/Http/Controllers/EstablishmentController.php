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

    // get /establishment/showById/transaction/{transaction_id}
    public function readTransactionById($transaction_id)
    {
        try {
            $productIds = DB::table('product_transaction')
                ->where('transaction_id', $transaction_id)
                ->pluck('products_id');

            if ($productIds->isEmpty()) {
                return response()->json(['message' => 'No products found for this transaction'], 404);
            }

            $products = DB::table('products')
                ->whereIn('id', $productIds)
                ->get();

            return response()->json($products, 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching transaction: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching transaction'], 500);
        }
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

            $products = DB::table('product_transaction')
                ->join('products', 'product_transaction.products_id', '=', 'products.id')
                ->where('product_transaction.transaction_id', $transaction->id)
                ->get(['products.id', 'products.name', 'products.price', 'products.quantity']);
    

            $expense = 0;
            foreach ($products as $product) {
                $expense += $product->price * $product->quantity;
            }

            $formattedTransaction = [
                'id' => $transaction->id,
                'date' => $transaction->date,
                'establishment_name' => $establishment->name,
                'establishment_code' => $establishment->code,
                'products' => $products,
                'expense' => $expense,
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

    // post /establishment/update/transaction
    public function updateTransaction(Request $request)
    {
        if (!$request->filled(['type', 'contents'])) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $type = $request->input('type');
        $contents = $request->input('contents');

        if ($type === 'products') {
            try {
                DB::beginTransaction();

                foreach ($contents as $productData) {
                    $productId = $productData['id'];
                    $product = DB::table('products')->where('id', $productId)->first();
                    
                    if (!$product) {
                        return response()->json(['error' => 'Product not found'], 404);
                    }
                    if (isset($productData['deleted']) && $productData['deleted']) {
                        DB::table('product_transaction')->where('products_id', $productId)->delete();
                        DB::table('products')->where('id', $productId)->delete();
                    } else {
                        $productDataWithoutId = collect($productData)->except('id')->toArray();
                        DB::table('products')->where('id', $productId)->update($productDataWithoutId);
                    }
                }

                DB::commit();

                return response()->json(['message' => 'Products updated successfully'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error updating transaction: ' . $e->getMessage());
                return response()->json(['error' => 'An error occurred while updating products'], 500);
            }
        } else {
            return response()->json(['error' => 'Invalid table specified'], 400);
        }
    }


    // post /establishment/delete/product
    public function deleteProduct(Request $request)
    {

    }

    // post /establishment/delete/transaction
    public function deleteTransaction(Request $request)
    {
        $type = $request->input('type');
        $contents = $request->input('contents');

        DB::beginTransaction();

        try {
            $transactionId = $contents['id'];
            $productIds = DB::table('product_transaction')
                ->where('transaction_id', $transactionId)
                ->pluck('products_id');


            DB::table('product_transaction')->where('transaction_id', $transactionId)->delete();
            DB::table('products')->whereIn('id', $productIds)->delete();
            DB::table('transaction')->where('id', $transactionId)->delete();

            DB::commit();

            return response()->json(['message' => 'Transaction and associated products deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting transaction: ' . $e->getMessage());

            return response()->json(['message' => 'Error deleting transaction'], 500);
        }
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