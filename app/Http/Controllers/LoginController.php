<?php

namespace App\Http\Controllers;

use App\Models\Senior;
use App\Models\SuperAdmin;
use App\Models\Barangay;
use App\Models\Town;
use App\Models\Establishment;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\SuperAdminController;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $userName = $request->input('username');

        $senior = Senior::where('username', $userName)->first();
        $superAdmin = SuperAdmin::where('username', $userName)->first();
        $barangay = Barangay::where('username', $userName)->first();
        $town = Town::where('username', $userName)->first();
        $establishment = Establishment::where('username', $userName)->first();

        if ($senior) {
            return response()->json(["message" => "User found.", "client_type" => "basic"], 200);
        } elseif ($superAdmin || $barangay || $town || $establishment) {
            return response()->json(["message" => "User found.", "client_type" => "admin"], 200);
        } else {
            return response()->json(["message" => "User not found."], 404);
        }
    }

    public function index()
    {
        return response()->json([], 200);
    }

    public function validateLogin(Request $request)
    {
        $password = $request->input('password');

        $superAdmin = SuperAdmin::where('password', $password)->first();
        $barangay = Barangay::where('password', $password)->first();
        $town = Town::where('password', $password)->first();
        $establishment = Establishment::where('password', $password)->first();

        // refine to avoid attack on unathorized access
        if ($superAdmin){
            return response()->json(["message" => "Success", "client_type" => "admin_0"], 200);
        }elseif($town){
            return response()->json(["message" => "Success", "client_type" => "admin_1"], 200);
        }elseif($barangay){
            return response()->json(["message" => "Success", "client_type" => "admin_2"], 200);
        }elseif($establishment){
            return response()->json(["message" => "Success", "client_type" => "establishment"], 200); 
        }else {
            return response()->json(["message" => "Invalid password"], 401);
        }
    }

}
