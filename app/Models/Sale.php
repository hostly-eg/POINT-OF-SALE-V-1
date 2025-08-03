<?php

// app/Models/Sale.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = ['total_price', 'customer_name'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
