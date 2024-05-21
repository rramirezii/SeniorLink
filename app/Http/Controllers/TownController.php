<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TownController extends BaseController
{
    // get /town/dashboard
    public function dashboard()
    {
        return response()->json(["role" => "admin_1"], 200); //edit to return session as well
    }

    // creates a barangay
    // post /town/create
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

        $rules = $this -> makeRulesRequired($rules);
        $validator = Validator::make($contents, $rules);

        if ($validator->fails()) {
            throw new \Exception($this->generateErrorMessage($validator));
        }

        DB::table($table)->insert($contents);

        return $id; // Return the newly created entity's ID
    }

    // get /town/{town_username}/show/{clients}
    public function read($client, $town_username)
    {
        $fields = '*';
        $extraClause = '';

        switch($client){
            case 'barangay': // maybe add statistics on the number of seniors
                $fields = 'id, name, username';
                $extraClause = 'WHERE town_id= :town_id'; // dynamic suppmentation
                break;
            case 'senior': //fix this to return seniors using the town_username and take the barangay name
                $fields = 'senior.id, senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
                $extraClause = 'LEFT JOIN barangay 
                                ON senior.barangay_id = barangay.id 
                                WHERE barangay.town_id = :town_id';
                break;
            default:
                return response()->json(['error' => 'Unknown client type'], 404);
        }

        if (is_null($town_username)||empty($town_username)) {
            return response()->json(['error' => 'town_username parameter is required'], 400);
        }

        return $this->generateReadResponse($fields, $extraClause, $client, ['town_id' => $town_username]);
    }

    //read seniors from a barangay
   // get /town/{town_username}/show/barangay/{barangay_username}/{client}
    public function readSenior($client, $town_username, $barangay_username)
    {
        //create a reader here
        // Validate the client type
        if ($client !== 'senior') {
            return response()->json(['error' => 'Unknown client type'], 404);
        }

        // Validate town_username and barangay_username parameters
        if (is_null($town_username) || empty($town_username) || is_null($barangay_username) || empty($barangay_username)) {
            return response()->json(['error' => 'town_username and barangay_username parameters are required'], 400);
        }

        // Fetch the town_id using town_username
        $town = DB::table('town')->where('username', $town_username)->first();
        if (!$town) {
            return response()->json(['error' => 'Invalid town username'], 404);
        }
        $town_id = $town->id;

        // Fetch the barangay_id using barangay_username and town_id
        $barangay = DB::table('barangay')
            ->where('username', $barangay_username)
            ->where('town_id', $town_id)
            ->first();
        if (!$barangay) {
            return response()->json(['error' => 'Invalid barangay username or town_id'], 404);
        }
        $barangay_id = $barangay->id;

        // Define the fields to be selected
        $fields = [
            'senior.id',
            'senior.osca_id',
            'senior.fname',
            'senior.mname',
            'senior.lname',
            'barangay.name as barangay_name',
            'senior.birthdate',
            'senior.contact_number',
            'senior.username',
            'senior.profile_image',
            'senior.qr_image'
        ];

        // Fetch the senior data based on barangay_id
        $seniors = DB::table('senior')
            ->select($fields)
            ->leftJoin('barangay', 'senior.barangay_id', '=', 'barangay.id')
            ->where('barangay.id', $barangay_id)
            ->get();

        // Check if any seniors were found
        if ($seniors->isEmpty()) {
            return response()->json(['error' => 'No seniors found for the given barangay'], 404);
        }

        // Return the fetched senior data
        return response()->json($seniors, 200);
    }

    // post /town/update
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

    // delete a barangay
    // post /town/delete
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
        return "required|string|in:barangay,town";
    }

    private function getStrictScope()
    {
        return "required|string|in:barangay";
    }
}