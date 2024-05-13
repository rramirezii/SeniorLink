<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Base
{
    protected $table = 'transaction';
    protected $guarded = [];

    // Define relationships
    public function senior()
    {
        return $this->belongsTo(Senior::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_transaction', 'transaction_id', 'product_id');
    }

    public static $rules = [
        'senior_id' => 'required|integer|exists:senior,id',
        'establishment_id' => 'required|integer|exists:establishment,id',
        'date' => 'required|date'
    ];
}
