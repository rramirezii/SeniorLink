<?php

namespace App\Http\Controllers;

use App\Models\Senior;
use App\Models\SuperAdmin;
use App\Models\Barangay;
use App\Models\Town;
use App\Models\Establishment;
use Illuminate\Http\Request;

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
            return view('birthdate_selector');
        } elseif ($superAdmin || $barangay || $town || $establishment) {
            return view('password_login');
        } else {
            return redirect()->back()->with('error', 'User ID not found.');
        }
    }

    public function index()
    {
        return view('login');
    }

    public function test_login(Request $request)
    {
        $password = $request->input('password');

        $superAdmin = SuperAdmin::where('password', $password)->first();
        $barangay = Barangay::where('password', $password)->first();
        $town = Town::where('password', $password)->first();
        $establishment = Establishment::where('password', $password)->first();

        // refine to avoid attack on unathorized access
        if ($superAdmin){
            print("kokoo");
            return app(SuperAdminController::class)->dashboard();
        }elseif($town){
            return view(); // town dashboard
        }elseif($barangay){
            return view(); // barangay dashboard
        }elseif($establishment){
                return view(); // establishment dashboard   
        }else {
            return redirect()->back()->with('error', 'Invalid password.');
        }
    }

}
