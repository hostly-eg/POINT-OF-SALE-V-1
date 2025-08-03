<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    // الحقول المسموح تعبئتها جماعيًا
    protected $fillable = [
        'description',
        'amount',
        'expense_date',
    ];

    // تحويل تاريخ النفقة لكائن Carbon تلقائيًا
    protected $dates = [
        'expense_date',
    ];
}
