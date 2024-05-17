<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{
    // on success login; get method show dashboard
    public function dashboard()
    {
        return response()->json(["client_type" => "admin_0"], 200); //edit to return session as well
    }

    // get method show create; /create route
    public function create()
    {
        return response()->json(["client_type" => "admin_0"], 200);
    }

    // post method create; request must include what type of creation; /create
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
        // Specific validation
        $validator = Validator::make($contents, $rules);

        if ($validator->fails()) {
            throw new \Exception($this->generateErrorMessage($validator));
        }

        // Insert into the database
        DB::table($table)->insert($contents);
    }

    //get method read; return a list of clients
    public function readClient()
    {
        // retrieval here
    }

    private function readTown()
    {

    }

    private function generateErrorMessage($originator)
    {
        $messages = $originator->errors()->all();
        return implode(', ', $messages);
    }
}
