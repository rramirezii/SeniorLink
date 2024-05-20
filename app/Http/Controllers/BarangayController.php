<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TownController extends BaseController
{
    // get /town/dashboard
    public function dashboard()
    {
        return response()->json(["role" => "admin_1"], 200); //edit to return session as well
    }

    // post /town/create/{client}
    public function create(Request $request)
    {
        $validation = $this -> checkRequest($request,$this->getScope());

        if($validation['status'] !== 200) {        
            return response()->json($response['data'], $response['status']);
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

        $rules = $this -> makeRulesRequired($rules);
        $validator = Validator::make($contents, $rules);

        if ($validator->fails()) {
            throw new \Exception($this->generateErrorMessage($validator));
        }

        DB::table($table)->insert($contents);

        return $id; // Return the newly created entity's ID
    }

    // get /town/show/{clients}
    public function read($client, $townID)
    {
        $fields = '*';
        $extraClause = '';

        switch($client){
            case 'barangay': // maybe add statistics on the number of seniors
                $fields = 'id, name, username';
                $extraClause = 'WHERE town_id= :town_id'; // dynamic suppmentation
                break;
            case 'senior':
                $fields = 'senior.id, senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
                $extraClause = 'LEFT JOIN barangay 
                                ON senior.barangay_id = barangay.id 
                                WHERE town_id = :town_id';
                break;
            default:
                return response()->json(['error' => 'Unknown client type'], 404);
        }

        if (is_null($townID)) {
            return response()->json(['error' => 'townID parameter is required'], 400);
        }

        return $this->generateReadResponse($fields, $extraClause, $client, ['town_id' => $townID]);
    }

    // NOT YET Functional
    // get /town/show/{parent}/{client}
    public function readFromParent($client, $parent)
    {

    }

    //Not yet functional
    // get /town/{townID}/show/{grandparent}/{parent}/{client}   --- For transactions only under a senior with the username, brgy with the username
    public function readFromGrandparent($client, $parent, $grandparent, $townID)
    {
        if ($client != "transaction") {
            return response()->json(['error' => 'Invalid client type.'], 404);
        }

        $barangayExists = DB::table('barangay')->where('username', $grandparent)->exists();
        if (!$barangayExists) {
            return response()->json(['error' => 'Invalid barangay.'], 404);
        }

        $seniorExists = DB::table('senior')->where('username', $parent)->exists();
        if (!$seniorExists) {
            return response()->json(['error' => 'Invalid senior.'], 404);
        }

        $fields = 'senior.id, senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, town.name as town_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image, products.name as product_name, products.quantity as product_quantity, products.price as product_price, transaction.date as transaction_date';
        $extraClause = 'LEFT JOIN barangay ON senior.barangay_id = barangay.id 
                        LEFT JOIN town ON barangay.town_id = town.id 
                        LEFT JOIN transaction ON senior.id = transaction.senior_id 
                        LEFT JOIN product_transaction ON transaction.id = product_transaction.transaction_id 
                        LEFT JOIN products ON product_transaction.products_id = products.id';

        return $this->generateReadResponse($fields, $extraClause, "senior");
    }

    // get /barangay/getall/{client} ; show the list of clients for updating and deleting
    public function getAll($client)
    {
        $fields = '*';
        $extraClause = '';

        switch ($client) {
            case 'barangay':
                $fields = 'id, name, username';
                $extraClause = 'WHERE town_id= :town_id';
                break;
            default:
                return response()->json(['error' => 'Client not part of scope'], 404);
        }

        return $this->generateReadResponse($fields, $extraClause, $client, ['town_id' => 1]);
    }

    // post /barangay/update/{clietnt}
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

    // post /barangay/delete/{client}
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

    private function getScope()
    {
        return "required|string|in:barangay,senior";
    }

    private function getStrictScope()
    {
        return "required|string|in:senior";
    }
}