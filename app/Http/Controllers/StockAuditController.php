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
    public function details(StockAudit $audit)
    {
        $audit->load([
            'product:id,name,price',
            'sale:id,customer_name,total_price,paid,created_at',
            'saleItem:id,sale_id,product_id,quantity,price,selling_price,location',
            'movement'
        ]);

        // نبني رد موحّد
        return response()->json([
            'id'          => $audit->id,
            'change_type' => $audit->change_type,
            'created_at'  => $audit->created_at->format('Y-m-d H:i'),
            'product'     => [
                'id'    => $audit->product->id ?? null,
                'name'  => $audit->product->name ?? null,
                'price' => $audit->product->price ?? null,
            ],
            'quantities'  => [
                'shop_old'  => $audit->old_shop_qty,
                'shop_new'  => $audit->new_shop_qty,
                'store_old' => $audit->old_store_qty,
                'store_new' => $audit->new_store_qty,
            ],
            'sale'        => $audit->sale ? [
                'id'            => $audit->sale->id,
                'customer_name' => $audit->sale->customer_name,
                'total_price'   => $audit->sale->total_price,
                'paid'          => $audit->sale->paid,
                'created_at'    => $audit->sale->created_at->format('Y-m-d H:i'),
            ] : null,
            'sale_item'   => $audit->saleItem ? [
                'quantity'      => $audit->saleItem->quantity,
                'price'         => $audit->saleItem->price,
                'selling_price' => $audit->saleItem->selling_price,
                'location'      => $audit->saleItem->location,
            ] : null,
            'movement'    => $audit->movement ? [
                'type'          => $audit->movement->type,
                'quantity'      => $audit->movement->quantity,
                'note'          => $audit->movement->note,
            ] : null,
        ]);
    }
}
