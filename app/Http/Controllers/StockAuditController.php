<?php

namespace App\Http\Controllers;

use App\Models\StockAudit;
use App\Models\Sale;
use Illuminate\Http\Request;

class StockAuditController extends Controller
{
    public function index(Request $request)
    {
        // فلاتر اختيارية
        $q         = trim($request->input('q'));            // بحث باسم المنتج
        $dateFrom  = $request->input('date_from');          // Y-m-d
        $dateTo    = $request->input('date_to');            // Y-m-d
        $perPage   = (int)($request->input('per_page', 20));

        // ============ سجلات الجرد ============
        $audits = StockAudit::with([
            // هات من المنتج الاسم وكميات المحل/المخزن
            'product:id,name,quantity_shop,quantity_store'
        ])
            ->when($q, function ($query) use ($q) {
                $query->whereHas('product', function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%");
                });
            })
            ->when($dateFrom, fn($query) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($query) => $query->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        // ============ المبيعات ============
        $sales = Sale::with([
            'items.product:id,name'
        ])
            ->when($dateFrom, fn($query) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($query) => $query->whereDate('created_at', '<=', $dateTo))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('seals.index', compact('audits', 'sales', 'q', 'dateFrom', 'dateTo'));
    }
}
