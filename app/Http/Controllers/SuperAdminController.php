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
            switch ($type) {
                case 'town':
                    $this->createEntity($contents, 'town', [
                        'name' => 'required|string|max:255',
                        'zip_code' => 'required|integer|unique:town',
                        'username' => 'required|string|unique:town|max:255',
                        'password' => 'required|string|max:255',
                    ]);
                    break;
                case 'establishment':
                    $this->createEntity($contents, 'establishment', [
                        'name' => 'required|string|max:255',
                        'code' => 'required|integer|unique:establishment',
                        'address' => 'required|string|max:255',
                        'username' => 'required|string|unique:establishment|max:255',
                        'password' => 'required|string|max:255',
                    ]);
                    break;
                case 'superadmin':
                    $this->createEntity($contents, 'super_admin', [
                        'name' => 'required|string|max:255',
                        'username' => 'required|string|unique:super_admin|max:255',
                        'password' => 'required|string|max:255',
                    ]);
                    break;
                default:
                    return response()->json(['error' => 'Unknown type'], 400);
            }

            return response()->json(['message' => 'Creation successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Creation failed', 'message' => $e->getMessage()], 400);
        }
    }

    private function createEntity($contents, $table, $rules)
    {
        $validator = Validator::make($contents, $rules);

        if ($validator->fails()) {
            throw new \Exception($this->generateErrorMessage($validator));
        }

        DB::table($table)->insert($contents);
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
            switch ($type) {
                case 'town':
                    $this->updateEntity($contents, 'town', [
                        'id' => 'required|integer',
                        'name' => 'string|max:255',
                        'zip_code' => 'integer|unique:town,zip_code,' . $contents['id'],
                        'username' => 'string|unique:town,username,' . $contents['id'] . '|max:255',
                        'password' => 'string|max:255',
                    ]);
                    break;
                case 'establishment':
                    $this->updateEntity($contents, 'establishment', [
                        'id' => 'required|integer',
                        'name' => 'string|max:255',
                        'code' => 'integer|unique:establishment,code,' . $contents['id'],
                        'address' => 'string|max:255',
                        'username' => 'string|unique:establishment,username,' . $contents['id'] . '|max:255',
                        'password' => 'string|max:255',
                    ]);
                    break;
                case 'super_admin':
                    $this->updateEntity($contents, 'super_admin', [
                        'id' => 'required|integer',
                        'name' => 'string|max:255',
                        'username' => 'string|unique:super_admin,username,' . $contents['id'] . '|max:255',
                        'password' => 'string|max:255',
                    ]);
                    break;
                default:
                    return response()->json(['error' => 'Unknown type'], 400);
            }
            return response()->json(['message' => 'Update successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed', 'message' => $e->getMessage()], 400);
        }
    }

    private function updateEntity($contents, $table, $rules)
    {
        // another validation for the rules
        $validator = Validator::make($contents, $rules);

        if ($validator->fails()) {
            throw new \Exception('Validation failed: ' . json_encode($validator->errors()));
        }

        if (!isset($contents['id'])) {
            throw new \Exception('ID not provided for update');
        }

        $id = $contents['id'];

        unset($contents['id']); // remove id from content to avoid update of id

        $affected = \DB::table($table)->where('id', $id)->update($contents);

        if ($affected === 0) {
            throw new \Exception('No record found to update or no changes made');
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
}
