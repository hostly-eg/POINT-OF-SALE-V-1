<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $brands = Brand::all();
        $categories = Category::all();

        return view('pos.index', compact('products', 'brands', 'categories'));
    }

    public function sell(Request $request)
    {
        $request->validate([
            'items_json' => 'required|string',
            'customer_name' => 'nullable|string|max:255',
        ]);

        $items = json_decode($request->items_json, true);

        if (!is_array($items) || empty($items)) {
            return back()->with('error', 'السلة فارغة أو غير صالحة');
        }

        DB::beginTransaction();

        try {
            $total = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);

            $sale = Sale::create([
                'customer_name' => $request->customer_name, // <-- حفظ اسم العميل
                'total_price'   => $total,
            ]);

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("الكمية غير كافية للمنتج: {$product->name}");
                }

                // إضافة عنصر البيع
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);

                // تحديث الكمية
                $product->decrement('quantity', $item['quantity']);

                // تسجيل الجرد
                StockAudit::create([
                    'product_id'    => $product->id,
                    'old_quantity'  => $product->quantity + $item['quantity'],
                    'new_quantity'  => $product->quantity,
                    'difference'    => -1 * $item['quantity'],
                    'change_type'   => 'عملية بيع',
                ]);
            }

            DB::commit();
            return back()->with('success', 'تم تسجيل البيع بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطأ أثناء البيع: ' . $e->getMessage());
        }
    }
}
