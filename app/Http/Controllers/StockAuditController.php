<?php

namespace App\Http\Controllers;

use App\Models\StockAudit;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAuditController extends Controller
{
    public function index()
    {
        $audits = StockAudit::with('product')->latest()->get();

        // في حالة عندك موديل Sale ومربوط بـ sale_items
        $sales = Sale::with('items.product') // جبنا كل العناصر المرتبطة بكل فاتورة
            ->orderBy('created_at', 'desc')
            ->get();

        return view('seals.index', compact('audits', 'sales'));
    }
}
