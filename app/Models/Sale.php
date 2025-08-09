<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $dates = ['created_at', 'updated_at'];

    // ✅ ضفنا paid و remaining و user_id
    protected $fillable = ['total_price', 'customer_name', 'paid', 'remaining'];
    protected $casts = [
        'total_price' => 'decimal:2',
        'paid'        => 'decimal:2',
        'remaining'   => 'decimal:2',
    ];

    public function parent()
    {
        return $this->belongsTo(Sale::class, 'parent_sale_id');
    }
    public function returns()
    {
        return $this->hasMany(Sale::class, 'parent_sale_id');
    }
    // ✅ علاقة مع المنتجات (مش مباشرة، غالبًا من SaleItem)
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
