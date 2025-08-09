<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();

        // ========================
        // 1. إجمالي المبيعات (الأسبوعية) - فقط من المبيعات
        // ========================
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::SATURDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::FRIDAY);


        $weeklySales = Sale::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('paid');

        // ========================
        // 2. مبيعات اليوم - فقط من المبيعات
        // ========================
        $dailySales = Sale::whereDate('created_at', today())
            ->sum('paid');


        // ========================
        // 3. مبيعات الشهر - فقط من المبيعات
        // ========================
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();
        $monthlySales = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('paid');


        // ========================
        // 4. النفقات
        // ========================
        $dailyExpenses   = Expense::whereDate('expense_date', today())->sum('amount');
        $weeklyExpenses  = Expense::whereBetween('expense_date', [$startOfWeek, $endOfWeek])->sum('amount');
        $monthlyExpenses = Expense::whereBetween('expense_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount');
        $totalExpenses   = Expense::sum('amount');

        return view('dashboard', compact(
            'totalProducts',
            'dailySales',
            'weeklySales',
            'monthlySales',
            'dailyExpenses',
            'weeklyExpenses',
            'monthlyExpenses',
            'totalExpenses'
        ));
    }
}
