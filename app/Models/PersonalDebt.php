<?php

// app/Models/Debt.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalDebt extends Model
{
    protected $fillable = [
        'company_name',
        'debt_date',
        'type',
        'user_id',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(PersonalDebtItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
