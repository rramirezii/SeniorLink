<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Senior extends Base
{
    protected $table = 'senior';
    protected $guarded = [];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function transactions()
    {
        return $this->belongsToMany(Establishment::class, 'transaction', 'senior_id', 'establishment_id')
                    ->withPivot('date');
    }

    public static $rules = [
        'osca_id' => 'required|string|max:100',
        'fname' => 'required|string|max:100',
        'mname' => 'required|string|max:100',
        'lname' => 'required|string|max:100',
        'barangay_id' => 'required|integer|exists:barangay,id',
        'birthday' => 'required|date',
        'cellphone_number' => 'required|string|max:11',
        'profile_image' => 'file',
        'qr_image' => 'file', // generate in our app

    ];
}
