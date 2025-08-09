<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAudit extends Model
{
    // app/Models/StockAudit.php
    protected $fillable = [
        'product_id',
        'change_type',
        'old_shop_qty',
        'old_store_qty',
        'new_shop_qty',
        'new_store_qty',
    ];

    protected $casts = [
        'old_shop_qty'  => 'float',
        'new_shop_qty'  => 'float',
        'old_store_qty' => 'float',
        'new_store_qty' => 'float',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
