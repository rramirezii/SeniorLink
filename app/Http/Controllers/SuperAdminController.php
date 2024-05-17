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

    public function create()
    {
        return response()->json(["client_type" => "admin_0"], 200);
    }

    public function create(Request $request)
    {
        // first step validation 
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:town,establishment,super_admin',
            'contents' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid request', 'messages' => $validator->errors()], 400);
        }
        
        $type = $request->input('type');
        $contents = $request->input('contents');

        if (!$type || !$contents) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

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
                $fields = 'name, zip_code, username';
                break;
            case 'barangay':
                $fields = 'barangay.name, town.name as town, barangay.username';
                $extraClause = 'JOIN town ON town.id = barangay.town_id';
                break;
            case 'senior':
                $fields = 'senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, town.name as town_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
                $extraClause = 'LEFT JOIN barangay ON senior.barangay_id = barangay.id LEFT JOIN town ON barangay.town_id = town.id';
                break;
            case 'establishment':
                $fields = 'name, code, address, username';
                break;
            case 'super_admin':
                $fields = 'name, username';
                break;
            default:
                return response()->json(['error' => 'Unknown client type'], 404);
        }

        return $this->generateReadResponse($fields, $extraClause, "senior");
    }

    // route should be get /$parent/$client
    public function read($client, $parent) //only for barangay and senior; if $client = barangay, $parent is name of town; if $client = senior, $parent is name of barangay
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
                $fields = 'barangay.name, town.name as town, barangay.username';
                $extraClause = 'JOIN town ON town.id = barangay.town_id';
                break;
            case 'senior':
                $fields = 'senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, town.name as town_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
                $extraClause = 'LEFT JOIN barangay ON senior.barangay_id = barangay.id LEFT JOIN town ON barangay.town_id = town.id';
                break;
        }    
        
        return $this->generateReadResponse($fields, $extraClause, $client);
    }

    //rout should be get /$grandparent/$parent/$client
    public function read($client, $parent, $grandparent)
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

        $fields = 'senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, town.name as town_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
        $extraClause = 'LEFT JOIN barangay ON senior.barangay_id = barangay.id LEFT JOIN town ON barangay.town_id = town.id';

        return $this->generateReadResponse($fields, $extraClause, "senior");
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
