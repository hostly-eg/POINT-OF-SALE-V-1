<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class CustomerDebtController extends Controller
{
    // اختياري: لو عايز تجيب محتوى المودال من السيرفر
    public function edit(Sale $sale)
    {
        // رجّع blade صغير فيه فورم التعديل لو بتستخدم AJAX load
        return view('debts.customer._edit_modal', compact('sale'));
    }

    // تحديث المدفوع + المتبقي
    public function update(Request $request, Sale $sale)
    {
        $data = $request->validate([
            'paid' => 'required|numeric|min:0',
        ]);

        $paid = (float) $data['paid'];
        $total = (float) $sale->total_price;

        // المتبقي لا يقل عن صفر
        $remaining = max($total - $paid, 0);

        $sale->update([
            'paid'      => $paid,
            'remaining' => $remaining,
        ]);

        // لو المودال شغال بـ AJAX:
        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'paid' => number_format($sale->paid, 2, '.', ''),
                'remaining' => number_format($sale->remaining, 2, '.', ''),
                'status_badge' => $sale->remaining <= 0 ? 'تم السداد' : 'لم يتم السداد',
            ]);
        }

        // عادي redirect
        return back()->with('success', 'تم تحديث المديونية.');
    }
}
