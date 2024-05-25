<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $userName = $request->input('username');

        $senior = DB::table('senior')->where('username', $userName)->first();
        $superAdmin = DB::table('super_admin')->where('username', $userName)->first();
        $barangay = DB::table('barangay')->where('username', $userName)->first();
        $town = DB::table('town')->where('username', $userName)->first();
        $establishment = DB::table('establishment')->where('username', $userName)->first();

        if ($senior) {
            return response()->json(["message" => "User found.", "role" => "basic"], 200);
        } elseif ($superAdmin || $barangay || $town || $establishment) {
            return response()->json(["message" => "User found.", "role" => "admin"], 200);
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

        $superAdmin = DB::table('super_admin')->where('password', $password)->first();
        $barangay = DB::table('barangay')->where('password', $password)->first();
        $town = DB::table('town')->where('password', $password)->first();
        $establishment = DB::table('establishment')->where('password', $password)->first();

        // refine to avoid attack on unauthorized access
        if ($superAdmin){
            return response()->json(["message" => "Success", "role" => "admin_0"], 200);
        }elseif($town){
            return response()->json(["message" => "Success", "role" => "admin_1"], 200);
        }elseif($barangay){
            return response()->json(["message" => "Success", "role" => "admin_2"], 200);
        }elseif($establishment){
            return response()->json(["message" => "Success", "role" => "establishment"], 200); 
        }else {
            return response()->json(["message" => "Invalid password"], 401);
        }
    }
}
