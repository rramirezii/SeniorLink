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
        return response()->json(["role" => "establishment"], 200); //edit to return session as well
    }

    // post /establishment/create
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

    public function createProduct(Request $request){
        //create a product
    }

    private function createEntity($table, $contents)
    {
        $rules = $this->getRules($table);

        if (empty($rules)) {
            throw new \Exception('Invalid table name or missing validation rules.');
        }

        // add additional validation for transaction; must contain date, senior_id, estab_id

        // add additional validation for productts

        $validator = Validator::make($contents, $rules); // edit content to contain only the product array or the transaction array

        if ($validator->fails()) {
            throw new \Exception($this->generateErrorMessage($validator));
        }

        // make this transaction so that create must be made to product, transaction, and transaction_products table at once
        DB::table($table)->insert($contents);

        return $id; // Return the newly created entity's ID
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

    //get /establishment/{establishment_username}/show/senior/{senior_username}/{client}
    public function readSenior($client, $establishment_username, $senior_username)
    {
        //create a reader here
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