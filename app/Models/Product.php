<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Base
{
    protected $table = 'products';
    protected $guarded = [];

    // Define relationships

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'product_transaction', 'product_id', 'transaction_id');
    }

    public static $rules = [
        'name' => 'required|string|max:100',
        'quantity' => 'required|integer',
        'price' => 'required|numeric|between:0.01,9999999.99',
    ];
}
