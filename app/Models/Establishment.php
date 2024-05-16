<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Establishment extends Base
{
    protected $table = 'establishment';
    protected $guarded = [];

    public function transactions()
    {
        return $this->belongsToMany(Senior::class, 'transaction', 'establishment_id', 'senior_id')
                    ->withPivot('date');
    }

    public static $rules = [
        'name' => 'required|string|max:50',
        'code' => 'required|string|max:50', // use a self-generator for the code
        'address' => 'required|string|max:50',
        'password' => 'required|string|max:255',
    ];
}
