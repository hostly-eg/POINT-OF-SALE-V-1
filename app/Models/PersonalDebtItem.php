<?php
// app/Models/DebtItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalDebtItem extends Model
{
    protected $fillable = ['personal_debt_id', 'product_id', 'quantity', 'price'];

    public function debt()
    {
        return $this->belongsTo(PersonalDebt::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
