<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTransaction extends BaseModel
{
    protected $table = 'product_transaction';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public static $rules = [
        'product_id' => 'required|integer|exists:product,id',
        'transaction_id' => 'required|integer|exists:transaction,id',
    ];
}