<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'quantity_added',
        'purchase_price_at_time',
        'supplier_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
