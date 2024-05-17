<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    // on success login; get method show dashboard
    public function dashboard()
    {
        return response()->json(["message" => "User found.", "client_type" => "admin_0"], 200); //edit to return session as well
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
            'type' => 'required|string|in:town,establishment,superadmin',
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
                    $this->createTown($contents);
                    break;
                case 'establishment':
                    $this->createEstablishment($contents);
                    break;
                case 'superadmin':
                    $this->createSuperAdmin($contents);
                    break;
                default:
                    return response()->json(['error' => 'Unknown type'], 400);
            }

            return response()->json(['message' => 'Creation successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Creation failed', 'message' => $e->getMessage()], 400);
        }
    }

    private function createTown($contents)
    {
        // specific validation
        $validator = Validator::make($contents->all(), [
            'type' => 'required|string|in:town,establishment,superadmin',
            'contents' => 'required|array',
            'contents.name' => 'required|string|max:255',
            'contents.population' => 'required_if:type,town|integer|min:0',
            
            'name' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $errorMessage = implode(', ', $messages);
            throw new \Exception($errorMessage);
        }
        
        //given the contents create a town in the town table
    }

    private function createEstablishment($contents)
    {
        //given the contents create a establishment in the establishment table
    }

    private function createSuperAdmin($contents)
    {
        //given the contents create a super admin in the superadmin table
    }

    //get method read; return a list of clients
    public function readClient()
    {
        // retrieval here
    }

    private function readTown()
    {

    }
}
