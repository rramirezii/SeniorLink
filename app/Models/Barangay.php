<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Base
{
    protected $table = 'barangay';
    protected $guarded = [];

    // Define relationships
    public function town()
    {
        return $this->belongsTo(Town::class);
    }

    public function seniors()
    {
        return $this->hasMany(Senior::class, 'barangay_id');
    }

    public static $rules = [
        'name' => 'required|string|max:50',
        'town_id' => 'required|integer|exists:town,id',
        'password' => 'required|string|max:255',
    ];
}
