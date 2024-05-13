<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    protected $table = 'super_admin';
    protected $guarded = [];

    public static $rules = [
        'name' => 'required|string|max:50',
        'username' => 'required|string|max:100|unique:super_admin',
        'password' => 'required|string|max:255',
    ];

    // functionaliteis for super admins
    // create towns, estab, view all 
}
