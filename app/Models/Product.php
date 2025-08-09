<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'brand_id',
        'category_id',
        'car_id',
        'price',
        'profit_margin',
        'quantity_shop',
        'quantity_store',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    public function getTotalQuantityAttribute()
    {
        return (int) $this->quantity_shop + (int) $this->quantity_store;
    }
}
