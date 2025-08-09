<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockAudit;
use App\Models\StockMovement;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $brands = Brand::all();
        $categories = Category::all();
        $users = UserDetail::all();

        return view('pos.index', compact('products', 'brands', 'categories', 'users'));
    }

    public function sell(Request $request)
    {
        $request->validate([
            'type'          => 'required|in:sale,return',
            'items_json'    => 'required|string',
            'customer_name' => 'nullable|string|max:255',
            'paid'          => 'required|numeric|min:0',
            'remaining'     => 'required|numeric|min:0',
        ]);

        $isReturn = $request->input('type') === 'return';
        $items = json_decode($request->items_json, true);

        if (!is_array($items) || empty($items)) {
            return back()->with('error', 'السلة فارغة أو غير صالحة');
        }

        DB::beginTransaction();
        try {
            $total = collect($items)->sum(fn($i) => $i['selling_price'] * $i['quantity']);

            // في المرتجع نخزّن القيم سالبة علشان التقارير تجمّع صح
            $sale = Sale::create([
                'type'          => $isReturn ? 'return' : 'sale',
                'parent_sale_id' => null, // لو هتربط بفاتورة أصلية اضبطه لاحقًا
                'customer_name' => $request->customer_name,
                'total_price'   => $isReturn ? -$total : $total,
                'paid'          => $isReturn ? -$request->paid : $request->paid,
                'remaining'     => $isReturn ? 0 : $request->remaining,
            ]);

            foreach ($items as $row) {
                $product  = Product::lockForUpdate()->findOrFail($row['product_id']);
                $qty      = (int)$row['quantity'];
                $price    = (float)$row['price'];
                $sellPrice = (float)$row['selling_price'];
                $location = $row['location'] ?? 'shop';

                // القيم القديمة
                $shopOld  = (int)$product->quantity_shop;
                $storeOld = (int)$product->quantity_store;

                // البيع ينقص، المرتجع يزيد
                if ($location === 'shop') {
                    if (!$isReturn && $shopOld < $qty) {
                        throw new \Exception("كمية المحل غير كافية للمنتج: {$product->name}");
                    }
                    $product->quantity_shop = $isReturn ? ($shopOld + $qty) : ($shopOld - $qty);
                } else { // store
                    if (!$isReturn && $storeOld < $qty) {
                        throw new \Exception("كمية المخزن غير كافية للمنتج: {$product->name}");
                    }
                    $product->quantity_store = $isReturn ? ($storeOld + $qty) : ($storeOld - $qty);
                }
                $product->save();

                // سطر الفاتورة
                $item = $sale->items()->create([
                    'product_id'     => $product->id,
                    'quantity'       => $qty,
                    'price'          => $price,
                    'selling_price'  => $sellPrice,
                    'location'       => $location,
                    'shop_qty_old'   => $shopOld,
                    'shop_qty_new'   => $product->quantity_shop,
                    'store_qty_old'  => $storeOld,
                    'store_qty_new'  => $product->quantity_store,
                ]);

                // سجلات التتبع

                StockAudit::create([
                    'product_id'    => $product->id,
                    'old_shop_qty'  => $shopOld,
                    'new_shop_qty'  => $product->quantity_shop,
                    'old_store_qty' => $storeOld,
                    'new_store_qty' => $product->quantity_store,
                    'change_type'   => $isReturn ? 'مرتجع بيع' : 'عملية بيع',
                    'sale_id'       => $sale->id,
                    'sale_item_id'  => $item->id,
                ]);


                StockMovement::create([
                    'product_id'    => $product->id,
                    'quantity'      => $qty,
                    'type'          => $isReturn ? 'sale_return' : 'sale',
                    'note'          => null,
                    'shop_qty_old'  => $shopOld,
                    'shop_qty_new'  => $product->quantity_shop,
                    'store_qty_old' => $storeOld,
                    'store_qty_new' => $product->quantity_store,
                ]);
            }

            DB::commit();
            return back()->with('success', $isReturn ? 'تم تسجيل المرتجع' : 'تم تسجيل البيع');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطأ: ' . $e->getMessage());
        }
    }
}
