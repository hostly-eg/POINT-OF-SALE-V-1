<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::orderBy('expense_date', 'desc')->get();
        return view('expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description'   => 'required|string|max:255',
            'amount'        => 'required|numeric|min:0',
            'expense_date'  => 'required|date',
        ]);

        Expense::create($request->only(['description', 'amount', 'expense_date']));

        return redirect()->route('expenses.index')->with('success', 'تمت إضافة النفقة بنجاح.');
    }

    public function edit(Expense $expense)
    {
        $expenses = Expense::orderBy('expense_date', 'desc')->get();
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'description'   => 'required|string|max:255',
            'amount'        => 'required|numeric|min:0',
            'expense_date'  => 'required|date',
        ]);

        $expense->update($request->only(['description', 'amount', 'expense_date']));

        return redirect()->route('expenses.index')->with('success', 'تم تعديل النفقة بنجاح.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'تم حذف النفقة بنجاح.');
    }
}
