<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TownController extends Controller
{
    public function dashboard()
    {
        return response()->json(["role" => "admin_1"], 200); //edit to return session as well
    }
}