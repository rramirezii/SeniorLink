<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{

    public function dashboard()
    {
        return response()->json(["client_type" => "admin_0"], 200); //edit to return session as well
    }

    // post
    public function create(Request $request)
    {
        // first step validation 
        $validation = $this -> checkRequest($request);

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

    public function read($client)
    {
        $fields = '*';
        $extraClause = '';

        switch($client){
            case 'town':
                $fields = 'id, name, zip_code, username';
                break;
            case 'barangay':
                $fields = 'barangay.id, barangay.name, town.name as town, barangay.username';
                $extraClause = 'JOIN town ON town.id = barangay.town_id';
                break;
            case 'senior':
                $fields = 'senior.id, senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, town.name as town_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
                $extraClause = 'LEFT JOIN barangay ON senior.barangay_id = barangay.id LEFT JOIN town ON barangay.town_id = town.id';
                break;
            case 'establishment':
                $fields = 'id, name, code, address, username';
                break;
            case 'super_admin':
                $fields = 'id, name, username';
                break;
            default:
                return response()->json(['error' => 'Unknown client type'], 404);
        }

        return $this->generateReadResponse($fields, $extraClause, "senior");
    }

    // route should be get /$parent/$client
    public function readFromParent($client, $parent) //only for barangay and senior; if $client = barangay, $parent is name of town; if $client = senior, $parent is name of barangay
    {
        switch ($client){
            case 'barangay':
                $townExists = DB::table('town')->where('name', $parent)->exists();
                if(!$townExists){
                    return response()->json(['error' => 'Invalid parent.'], 404);
                }
                break;
            case 'senior':
                $barangayExists = DB::table('barangay')->where('name', $parent)->exists();
                if(!$barangayExists){
                    return response()->json(['error' => 'Invalid parent.'], 404);
                }
                break;
            default:
                return response()->json(['error' => 'Unknown client type'], 404);
        }
    
        $fields = "*";
        $extraClause = "";
    
        switch ($client){
            case 'barangay':
                $fields = 'barangay.id, barangay.name, town.name as town, barangay.username';
                $extraClause = 'JOIN town ON town.id = barangay.town_id';
                break;
            case 'senior':
                $fields = 'senior.id, senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, town.name as town_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
                $extraClause = 'LEFT JOIN barangay ON senior.barangay_id = barangay.id LEFT JOIN town ON barangay.town_id = town.id';
                break;
        }    
        
        return $this->generateReadResponse($fields, $extraClause, $client);
    }

    //rout should be get /$grandparent/$parent/$client
    public function readFromGrandparent($client, $parent, $grandparent)
    {
        if($client != "senior"){
            return response()->json(['error' => 'Invalid client type.'], 404);
        }

        $townExists = DB::table('town')->where('name', $grandparent)->exists();
        if(!$townExists){
            return response()->json(['error' => 'Invalid town.'], 404);
        }

        $barangayExists = DB::table('barangay')->where('name', $parent)->exists();
        if(!$barangayExists){
            return response()->json(['error' => 'Invalid barangay.'], 404);
        }

        $fields = 'senior.id, senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, town.name as town_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
        $extraClause = 'LEFT JOIN barangay ON senior.barangay_id = barangay.id LEFT JOIN town ON barangay.town_id = town.id';

        return $this->generateReadResponse($fields, $extraClause, "senior");
    }

    // this is a post or put request
    public function update(Request $request)
    {
        // first step validation 
        $validation = $this -> checkRequest($request);

        if($validation['status'] !== 200) {        
            return response()->json($response['data'], $response['status']);
        }

        $type = $request->input('type');
        $contents = $request->input('contents');
        
        try {
            $this->updateEntity($contents, $type);
            return response()->json(['message' => 'Update successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed', 'message' => $e->getMessage()], 400);
        }
    }

    private function updateEntity($contents, $table, $rules)
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
        
        $contents = array_filter($contents, function ($value) { // remove the empty fields
            return $value !== "" && $value !== null;
        });

        $affected = DB::table($type)->where('id', $id)->update($contents);

        if ($affected === 0) {
            if (empty($contents)) {
              throw new \Exception('No changes provided for update.');
            } else {
              throw new \Exception('No record found to update or no valid changes made');
            }
          }
    }

    public function delete(Request $request){
        $validation = $this -> checkRequest($request);

        if($validation['status'] !== 200) {        
            return response()->json($response['data'], $response['status']);
        }

        $type = $request->input('type');
        $contents = $request->input('contents');

        try {
            $this->deleteEntity($contents, $type);
            return response()->json(['message' => 'Deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Deletion failed', 'message' => $e->getMessage()], 400);
        }
    }
        
    private function deleteEntity($contents, $table)
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

    private function checkRequest($request)
    {
        
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:town,establishment,super_admin',
            'contents' => 'required|array'
        ]);
    
        if ($validator->fails()) {
            return [
                'status' => 400,
                'data' => ['error' => 'Invalid request', 'messages' => $validator->errors()]
            ];
        }
    
        return [
            'status' => 200,
            'data' => []
        ];
    }

    private function generateReadResponse($fields, $extraClause, $table)
    {
        $result = DB::select("SELECT $fields FROM $table $extraClause");

        if(empty($result)){
            return response()->json(['error' => 'No data found'], 404);
        }

        return response()->json($result, 200);
    }

    private function generateErrorMessage($originator)
    {
        $messages = $originator->errors()->all();
        return implode(', ', $messages);
    }

    // move this out soon for other controllers to use
    private function makeRulesRequired($rules) 
    {
        $requiredRules = [];
      
        foreach ($rules as $key => $value) {
          $requiredRules[$key] = 'required|' . $value;
        }
      
        return $requiredRules;
    }

    // move this out soon for other controllers to use
    private function getRules($table)
    {
        switch ($table) {
            case 'town':
                return [
                    'name' => 'string|max:255',
                    'zip_code' => 'integer|unique:town',
                    'username' => 'string|unique:town|max:255',
                    'password' => 'string|max:255',
                ];
                break;
            case 'establishment':
                return [
                    'name' => 'string|max:255',
                    'code' => 'integer|unique:establishment',
                    'address' => 'string|max:255',
                    'username' => 'string|unique:establishment|max:255',
                    'password' => 'string|max:255',
                ];
                break;
            case 'superadmin':
                return [
                    'name' => 'string|max:255',
                    'username' => 'string|unique:super_admin|max:255',
                    'password' => 'string|max:255',
                ];
                break;
            default:
                return [];
        }
    }

    function transformRulesForUpdate($rules, $contents)
    {
        $updatedRules = [];

        // add the id field
        if (!isset($updatedRules['id'])) {
            $updatedRules['id'] = 'required|integer';
        }

        foreach ($rules as $field => $rule) {
            $updatedRules[$field] = $rule;

            // unique fields must add validation 
            if (strpos($rule, 'unique:') !== false) {
                list($validator, $uniqueConstraint) = explode(':', $rule);
                $tableColumn = explode(',', $uniqueConstraint)[0];

                $updatedRules[$field] = "$validator:{$tableColumn}," . $contents['id'];
            }
        }

        return $updatedRules;
    }
}
