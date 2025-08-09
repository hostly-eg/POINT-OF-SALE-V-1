<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'location',
        'shop_qty_old',
        'shop_qty_new',
        'store_qty_old',
        'store_qty_new',
        'price',
        'selling_price'
    ];
    public function sale()
    {
        return $this->belongsTo(\App\Models\Sale::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
