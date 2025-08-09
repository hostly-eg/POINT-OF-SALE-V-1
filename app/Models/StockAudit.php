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
                'sale_id','sale_item_id','movement_id',

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
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class);
    }
    public function movement()
    {
        return $this->belongsTo(StockMovement::class, 'movement_id');
    }

}
