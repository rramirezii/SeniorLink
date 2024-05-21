<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends BaseController
{
    // /admin/dashboard
    public function dashboard()
    {
        return response()->json(["role" => "admin_0"], 200); //edit to return session as well
    }

    // post /admin/create
    public function create(Request $request)
    {
        $validation = $this->checkRequest($request, $this->getScope());

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

        $rules = $this->makeRulesRequired($rules);
        $validator = Validator::make($contents, $rules);

        if ($validator->fails()) {
            throw new \Exception($this->generateErrorMessage($validator));
        }

        $id = DB::table($table)->insertGetId($contents);

        return $id; // Return the newly created entity's ID
    }

    // get /admin/show/{clients}
    public function read($client)
    {
        $fields = '*';
        $extraClause = '';

        switch ($client) {
            case 'town':
                $fields = 'id, name, zip_code, username';
                break;
            case 'barangay':
                $fields = 'barangay.id, barangay.name, town.name as town, barangay.username';
                $extraClause = 'JOIN town 
                                ON town.id = barangay.town_id';
                break;
            case 'senior':
                $fields = 'senior.id, senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, town.name as town_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
                $extraClause = 'LEFT JOIN barangay 
                                ON senior.barangay_id = barangay.id 
                                LEFT JOIN town 
                                ON barangay.town_id = town.id';
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

        return $this->generateReadResponse($fields, $extraClause, $client);
    }

    // get /admin/show/town/{town_username}/barangay
    public function readBarangay($town_username)
    {
        $town = DB::table('town')
                  ->where('username', $town_username)
                  ->first();

        if (!$town) {
            return response()->json(['error' => 'Barangay not found'], 404);
        }

        $table = 'barangay';
        $fields = 'barangay.id, barangay.username, barangay.name';
        $extraClause = 'JOIN town on town.id = :town_identification';

        return $this->generateReadResponse($fields, $extraClause, $table, ['town_identification' => $town_username]);
    }

    

    // get /admin/show/{$grandparent}/{$parent}/{$client}
    public function readFromGrandparent($client, $parent, $grandparent)
    {
        if ($client != "senior") {
            return response()->json(['error' => 'Invalid client type.'], 404);
        }

        $townExists = DB::table('town')->where('username', $grandparent)->exists();
        if (!$townExists) {
            return response()->json(['error' => 'Invalid town.'], 404);
        }

        $barangayExists = DB::table('barangay')->where('username', $parent)->exists();
        if (!$barangayExists) {
            return response()->json(['error' => 'Invalid barangay.'], 404);
        }

        $fields = 'senior.id, senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, town.name as town_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
        $extraClause = 'LEFT JOIN barangay 
                        ON senior.barangay_id = barangay.id 
                        LEFT JOIN town 
                        ON barangay.town_id = town.id';

        return $this->generateReadResponse($fields, $extraClause, "senior");
    }

    // get /admin/getall/{client} ; show the list of clients for updating and deleting
    public function getAll($client)
    {
        $fields = '*';
        $extraClause = '';

        switch ($client) {
            case 'town':
                $fields = 'id, name, zip_code, username';
                break;
            case 'establishment':
                $fields = 'id, name, code, address, username';
                break;
            case 'super_admin':
                $fields = 'id, name, username';
                break;
            default:
                return response()->json(['error' => 'Client not part of scope'], 404);
        }

        return $this->generateReadResponse($fields, $extraClause, $client);
    }

    // post /admin/update/{client}
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

    // post /admin/delete/{client}
    public function delete(Request $request)
    {
        $validation = $this->checkRequest($request, $this->getScope());

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
        return 'required|string|in:town,establishment,super_admin';
    }
}