<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BarangayController extends BaseController
{
    // get /brgy/dashboard
    public function dashboard()
    {
        return response()->json(["role" => "admin_2"], 200); //edit to return session as well
    }

    // post /barangay/create
    public function create(Request $request)
    {
        $validation = $this -> checkRequest($request,$this->getStrictScope());

        if ($validation !== null) {
            return $validation;
        }

        $type = $request->input('type');
        $contents = $request->input('contents');

        try {
            $id = $this->createEntity($type, $contents);
            return response()->json(['message' => 'Created successfully', 'id' => $id], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Creation failed', 'message' => $e->getMessage()], 400);
        }
    }

    private function createEntity($table, $contents)
    {
        $rules = $this->getRules($table);

        if (empty($rules)) {
            throw new \Exception('Invalid table name or missing validation rules.');
        }

        $validator = Validator::make($contents, $rules);

        if ($validator->fails()) {
            throw new \Exception($this->generateErrorMessage($validator));
        }

        $id = DB::table($table)->insertGetId($contents);

        $this->generateQR($id); //generate qr and save it

        return $id; // Return the newly created entity's ID
    }

    // get /barangay/{$barangay_username}/show/{client}
    public function read($client, $barangay_username)
    {
        $fields = '*';
        $extraClause = '';

        switch($client){
            case 'barangay':
                $fields = 'barangay.id, barangay.name, barangay.town_id, barangay.username';
                $extraClause = 'WHERE barangay.username = :barangay_identification';
                break;
            case 'senior':
                $fields = 'senior.id, senior.osca_id, senior.fname, senior.mname, senior.lname, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
                $extraClause = 'WHERE senior.barangay_id = :barangay_identification';
                break;
            default:
                return response()->json(['error' => 'Unknown client type'], 404);
        }

        if (is_null($barangay_username)) {
            return response()->json(['error' => 'barangay_username parameter is required'], 400);
        }

        if($client !== 'barangay'){
            try {
                $barangay_username = $this -> getIdByUsername($barangay_username, 'barangay');
            } catch (ModelNotFoundException $exception) {
                return response()->json(['error' => $exception->getMessage()], 400);
            }
        }

        return $this->generateReadResponse($fields, $extraClause, $client, ['barangay_identification' => $barangay_username]);
    }

    // get /barangay/{barangay_username}/show/senior/{senior_username}/transaction
    public function readTransaction($barangay_username, $senior_username)
    {
        $barangay = DB::table('barangay')
                  ->where('username', $barangay_username)
                  ->first();

        if (!$barangay) {
            return response()->json(['error' => 'Barangay not found'], 404);
        }

        $senior = DB::table('senior')
                ->where('username', $senior_username)
                ->where('barangay_id', $barangay->id)
                ->first();
        
        if (!$senior) {
            return response()->json(['error' => 'Senior not part of barangay'], 400);
        }

        $table = 'senior';
        $fields = 'senior.id as senior_id, senior.osca_id, senior.username as senior_username, CONCAT(senior.fname, " ", senior.mname, " ", senior.lname) as senior_name, products.name as product_name, products.quantity, products.price, transaction.date, establishment.name as establishment_name';
        $extraClause = 'JOIN transaction ON senior.id = transaction.senior_id
                        JOIN product_transaction ON transaction.id = product_transaction.transaction_id
                        JOIN products ON product_transaction.products_id = products.id
                        JOIN establishment ON transaction.establishment_id = establishment.id
                        WHERE senior.username = :senior_identification
                        GROUP BY senior.id, senior.osca_id, senior.username, senior.fname, senior.mname, senior.lname, products.name, products.quantity, products.price, transaction.date, establishment.name';
 
        return $this->generateReadResponse($fields, $extraClause, $table, ['senior_identification' => $senior_username]);
    }

    // post /barangay/update
    public function update(Request $request)
    {
        $validation = $this->checkRequest($request, $this->getScope());

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

    private function updateEntity($table, $contents)
    {
        $rules = $this->getRules($table);

        if (empty($rules)) {
            throw new \Exception('Invalid table name or missing validation rules.');
        }

        $rules = $this->transformRulesForUpdate($rules, $contents);
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

    // post /barangay/delete
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

    private function generateQR($seniorId)
    {
        $senior = DB::table('senior')->where('id', $seniorId)->first();

        if (!$senior) {
            return 0;
        }

        $qrCode = QrCode::format('png')->size(300)->generate($senior->username);

        DB::table('senior')->where('id', $seniorId)->update(['qr_image' => $qrCode]);
    }


    private function getScope()
    {
        return "required|string|in:barangay,senior";
    }

    private function getStrictScope()
    {
        return "required|string|in:senior";
    }
}