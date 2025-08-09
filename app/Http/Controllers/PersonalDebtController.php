<?php

namespace App\Http\Controllers;

use App\Models\PersonalDebt;
use App\Models\DebtItem;
use App\Models\PersonalDebtItem;
use App\Models\Product;
use Illuminate\Http\Request;

class PersonalDebtController extends Controller
{
    public function index()
    {
        $personalDebts = \App\Models\PersonalDebt::with('items')->latest()->get();
        $customerDebts = \App\Models\Sale::orderBy('created_at', 'desc')->get();

        return view('debts.index', compact('personalDebts', 'customerDebts'));
    }


    public function create()
    {
        $products = Product::all();
        return view('debts.create', compact('products'));
    }

    public function store(Request $request)
    {
        // حساب المجموع الإجمالي للمنتجات
        $items = $request->input('products');
        $totalAmount = 0;
        if (is_array($items)) {
            foreach ($items as $item) {
                $totalAmount += $item['price'];
            }
        }

        // إنشاء السجل الرئيسي في جدول personal_debts
        $personalDebt = PersonalDebt::create([
            'company_name' => $request->company_name,
            'debt_date'    => $request->debt_date,
            'total_amount' => $totalAmount,
            'paid_amount'         => $request->paid,
            'remaining_amount'    => $totalAmount - $request->paid,
            'status'       => ($totalAmount - $request->paid) == 0 ? 'تم السداد' : 'لم يتم السداد',
        ]);

        // حفظ المنتجات المرتبطة
        if (is_array($items)) {
            foreach ($items as $item) {
                PersonalDebtItem::create([
                    'personal_debt_id' => $personalDebt->id,
                    'product_id'       => $item['product_id'],
                    'quantity'         => $item['quantity'],
                    'price'            => $item['price'],
                ]);
            }
        }

        return redirect()->route('personal-debts.index')->with('success', 'تمت إضافة المديونية بنجاح.');
    }


    public function edit($id)
    {
        $debt = PersonalDebt::with('items')->findOrFail($id);
        $products = Product::all();
        return view('debts.edit', compact('debt', 'products'));
    }

    public function update(Request $request, $id)
    {
        $debt = PersonalDebt::findOrFail($id);

        // اقرأ المدفوع سواء جاء باسم paid أو paid_amount
        $paid = (float) $request->input('paid', $request->input('paid_amount', 0));

        $request->validate([
            // اعمل فاليديشن على القيمة اللي قرأتها بالفعل
            // لو عايز، تقدر تعمل custom validation بدون اسم حقل ثابت
        ]);

        $total     = (float) $debt->total_amount;
        $remaining = max($total - $paid, 0);

        $debt->update([
            'paid_amount'      => $paid,
            'remaining_amount' => $remaining,
            'status'           => $remaining <= 0 ? 'تم السداد' : 'لم يتم السداد',
        ]);

        return redirect()->route('personal-debts.index')->with('success', 'تم تحديث المدفوع بنجاح');
    }


    public function destroy($id)
    {
        $debt = PersonalDebt::findOrFail($id);
        $debt->items()->delete();
        $debt->delete();

        return redirect()->route('personal-debts.index')->with('success', 'تم حذف المديونية بنجاح');
    }
}
