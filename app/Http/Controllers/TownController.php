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
    public function read($client, $town_id=null)
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
                $extraClause = 'LEFT JOIN barangay ON senior.barangay_id = barangay.id WHERE town_id = :town_id';
                break;
            default:
                return response()->json(['error' => 'Unknown client type'], 404);
        }

        if (is_null($town_id)) {
            return response()->json(['error' => 'town_id parameter is required'], 400);
        }

        return $this->generateReadResponse($fields, $extraClause, $client, ['town_id' => $town_id]);
    }

    // get /town/show/{parent}/{client}
    public function readFromParent($client, $parent)
    {

    }

    // get /town/show/{grandparent}/{parent}/{client}
    public function readFromGrandparent($client, $parent, $grandparent)
    {

    }

    // get /town/getall/{client} ; show the list of clients for updating and deleting
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

        return $this->generateReadResponse($fields, $extraClause, $client);
    }

    // post /town/update/{clietnt}
    public function update(Request $request)
    {

    }

    private function updateEntity($table, $contents)
    {

    }

    // post /town/delete/{client}
    public function delete(Request $request)
    {

    }

    private function deleteEntity($table, $contents)
    {

    }

    private function getScope()
    {
        return "required|string|in:barangay,town";
    }
}