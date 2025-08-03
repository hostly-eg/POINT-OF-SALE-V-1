<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAudit extends Model
{
    protected $fillable = ['product_id', 'old_quantity', 'new_quantity', 'change_type', 'difference'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
