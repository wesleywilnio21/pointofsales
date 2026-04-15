<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'unit',
        'stock',
        'min_stock',
        'purchase_price',
        'sell_price',
        'conversion_factor',
        'bulk_unit',
        'bulk_price',
    ];

    public function stockEntries()
    {
        return $this->hasMany(StockEntry::class);
    }
}
