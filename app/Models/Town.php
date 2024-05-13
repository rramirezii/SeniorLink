<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Town extends Base
{
    protected $table = 'town';
    protected $guarded = [];

    // Define relationships
    public function barangays()
    {
        return $this->hasMany(Barangay::class, 'town_id');
    }

    public static $rules = [
        'name' => 'required|string|max:50',
        'zip_code' => 'required|integer|unique:town',
        'password' => 'required|string|max:255',
    ];
}
