<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockAudit;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index()
    {
        // هنعرض كل المنتجات بنفس الداتا
        $products = Product::select('id', 'name', 'price', 'quantity_shop', 'quantity_store')->orderBy('name')->get();

        return view('stock.index', compact('products'));
    }

    public function edit()
    {
        $products = Product::select('id', 'name', 'quantity_shop', 'quantity_store')->orderBy('name')->get();
        return view('stock.edit', compact('products'));
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'items.*.direction'  => 'required|in:shop_to_store,store_to_shop',
        ], [
            'items.required' => 'أضف صفًا واحدًا على الأقل.',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->items as $row) {
                $product   = Product::lockForUpdate()->findOrFail($row['product_id']);
                $qty       = (int)$row['qty'];
                $direction = $row['direction']; // shop_to_store | store_to_shop

                // القيم القديمة (قبل أي تعديل)
                $shopOld  = (int)$product->quantity_shop;
                $storeOld = (int)$product->quantity_store;

                $shopNew  = $shopOld;
                $storeNew = $storeOld;

                if ($direction === 'shop_to_store') {
                    if ($shopOld < $qty) throw new \Exception("الكمية بالمحل غير كافية للمنتج: {$product->name}");
                    $shopNew  = $shopOld  - $qty;
                    $storeNew = $storeOld + $qty;
                } else { // store_to_shop
                    if ($storeOld < $qty) throw new \Exception("الكمية بالمخزن غير كافية للمنتج: {$product->name}");
                    $storeNew = $storeOld - $qty;
                    $shopNew  = $shopOld  + $qty;
                }

                // حفظ المنتج مرة واحدة
                $product->quantity_shop  = $shopNew;
                $product->quantity_store = $storeNew;
                $product->save();

                // سجل الحركة (StockMovement)
                StockMovement::create([
                    'product_id'    => $product->id,
                    'quantity'      => $qty,
                    'type'          => $direction,                 // قيم ثابتة مفيدة للتقارير
                    'note'          => null,
                    'shop_qty_old'  => $shopOld,
                    'shop_qty_new'  => $shopNew,
                    'store_qty_old' => $storeOld,
                    'store_qty_new' => $storeNew,
                ]);

                // سجل الأوديت (StockAudit) بنص عربي واضح
                StockAudit::create([
                    'product_id'    => $product->id,
                    'old_shop_qty'  => $shopOld,
                    'new_shop_qty'  => $shopNew,
                    'old_store_qty' => $storeOld,
                    'new_store_qty' => $storeNew,
                    'change_type'   => $direction === 'shop_to_store'
                        ? 'تحويل من المحل للمخزن'
                        : 'تحويل من المخزن للمحل',
                ]);
            }

            DB::commit();
            return redirect()->route('stock.index')->with('success', 'تم تحويل المخزون بنجاح.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function updateStock(Request $request)
    {
        $items = $request->input('items', []);

        DB::transaction(function () use ($items) {
            foreach ($items as $row) {

                $product   = Product::lockForUpdate()->findOrFail($row['product_id']);
                $qty       = (int) $row['quantity'];
                $direction = $row['direction'];   // shop_to_store | store_to_shop | adjust_* ...
                $note      = $row['note'] ?? null;

                // خُد القيم القديمة أولًا
                $shopOld  = (int) $product->quantity_shop;
                $storeOld = (int) $product->quantity_store;

                // احسب القيم الجديدة
                $shopNew  = $shopOld;
                $storeNew = $storeOld;

                switch ($direction) {
                    case 'shop_to_store':
                        if ($shopOld < $qty) throw new \Exception('كمية المحل غير كافية');
                        $shopNew  = $shopOld  - $qty;
                        $storeNew = $storeOld + $qty;
                        break;

                    case 'store_to_shop':
                        if ($storeOld < $qty) throw new \Exception('كمية المخزن غير كافية');
                        $storeNew = $storeOld - $qty;
                        $shopNew  = $shopOld  + $qty;
                        break;

                    case 'adjust_shop_plus':
                        $shopNew  = $shopOld + $qty;
                        break;

                    case 'adjust_shop_minus':
                        if ($shopOld < $qty) throw new \Exception('كمية المحل غير كافية للتسوية');
                        $shopNew  = $shopOld - $qty;
                        break;

                    case 'adjust_store_plus':
                        $storeNew = $storeOld + $qty;
                        break;

                    case 'adjust_store_minus':
                        if ($storeOld < $qty) throw new \Exception('كمية المخزن غير كافية للتسوية');
                        $storeNew = $storeOld - $qty;
                        break;
                }

                // حدّث المنتج مرة واحدة فقط
                $product->quantity_shop  = $shopNew;
                $product->quantity_store = $storeNew;
                $product->save();  // ← لا داعي لتحديث/حفظ مرتين

                // سجل الحركة
                $movement =  StockMovement::create([
                    'product_id'    => $product->id,
                    'quantity'      => $qty,
                    'type'          => $direction,
                    'note'          => $note,
                    'shop_qty_old'  => $shopOld,
                    'shop_qty_new'  => $shopNew,
                    'store_qty_old' => $storeOld,
                    'store_qty_new' => $storeNew,
                ]);
                // سجل الأوديت (قيمه صح: القديم قبل التحديث والجديد بعده)
                StockAudit::create([
                    'product_id'    => $product->id,
                    'movement_id' => $movement->id,
                    'old_shop_qty'  => $shopOld,
                    'new_shop_qty'  => $shopNew,
                    'old_store_qty' => $storeOld,
                    'new_store_qty' => $storeNew,
                    'change_type'   => 'تحويل  من المخزن للمحل',
                ]);
            }
        });

        return back()->with('success', 'تم تحديث المخزون وتسجيل الحركة.');
    }
}
