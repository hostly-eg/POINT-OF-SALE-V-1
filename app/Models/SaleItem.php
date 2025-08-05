<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = ['sale_id', 'product_id', 'quantity', 'price', 'selling_price'];

    public function sale()
    {
        return $this->belongsTo(\App\Models\Sale::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
