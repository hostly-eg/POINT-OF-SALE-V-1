<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Expense;
use App\Models\SaleItem;
use App\Models\StockMovement;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();

        // ========================
        // 1. إجمالي المبيعات (الأسبوعية)
        // ========================

        // من حركة المخزون (سحب)
        $stockSalesWeek = StockMovement::with('product')
            ->where('type', 'out')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->get()
            ->sum(function ($movement) {
                return $movement->quantity * ($movement->product->price ?? 0);
            });

        // من عمليات البيع (POS)
        $posSalesWeek = SaleItem::with('product')
            ->whereHas('sale', function ($query) {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            })
            ->get()
            ->sum(function ($item) {
                return $item->quantity * ($item->product->price ?? 0);
            });

        $weeklySales = $stockSalesWeek + $posSalesWeek;

        // ========================
        // 2. مبيعات اليوم
        // ========================

        $stockSalesToday = StockMovement::with('product')
            ->where('type', 'out')
            ->whereDate('created_at', today())
            ->get()
            ->sum(function ($movement) {
                return $movement->quantity * ($movement->product->price ?? 0);
            });

        $posSalesToday = SaleItem::with('product')
            ->whereHas('sale', function ($query) {
                $query->whereDate('created_at', today());
            })
            ->get()
            ->sum(function ($item) {
                return $item->quantity * ($item->product->price ?? 0);
            });

        $dailySales = $stockSalesToday + $posSalesToday;

        // ========================
        // 3. مبيعات الشهر
        // ========================

        $stockSalesMonth = StockMovement::with('product')
            ->where('type', 'out')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->get()
            ->sum(function ($movement) {
                return $movement->quantity * ($movement->product->price ?? 0);
            });

        $posSalesMonth = SaleItem::with('product')
            ->whereHas('sale', function ($query) {
                $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
            })
            ->get()
            ->sum(function ($item) {
                return $item->quantity * ($item->product->price ?? 0);
            });

        $monthlySales = $stockSalesMonth + $posSalesMonth;

        // ========================
        // 4. النفقات
        // ========================

        $dailyExpenses   = Expense::whereDate('expense_date', today())->sum('amount');
        $weeklyExpenses  = Expense::whereBetween('expense_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount');
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
