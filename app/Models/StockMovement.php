<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'type',
        'note',
        'shop_qty_old',
        'shop_qty_new',
        'store_qty_old',
        'store_qty_new',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
