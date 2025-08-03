<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\StockAudit;

class StockMovementController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $movements = StockMovement::with('product')->latest()->get();

        return view('stock.index', compact('products', 'movements'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:in,out',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->type === 'out') {
            if ($request->quantity > $product->quantity) {
                return redirect()->route('stock.index')->with('error', 'الكمية المسحوبة أكبر من الكمية المتوفرة');
            }
            $product->decrement('quantity', $request->quantity);
        } else {
            $product->increment('quantity', $request->quantity);
        }

        StockMovement::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'type' => $request->type,
            'note' => $request->note,
        ]);

        return redirect()->route('stock.index')->with('success', 'تمت العملية بنجاح');
    }

    // حركة دخول
    public function storeIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'note'       => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        $oldQty = $product->quantity;

        StockMovement::create([
            'product_id' => $product->id,
            'quantity'   => $request->quantity,
            'type'       => 'in',
            'note'       => $request->note,
        ]);

        $product->increment('quantity', $request->quantity);

        // تسجيل الجرد
        StockAudit::create([
            'product_id' => $product->id,
            'old_quantity' => $oldQty,
            'new_quantity' => $product->quantity,
            'difference' => $product->quantity - $oldQty,
            'change_type' => 'إضافة مخزون'
        ]);

        return redirect()->route('stock.index')->with('success', 'تمت إضافة الكمية للمخزون.');
    }


    // حركة خروج
    public function storeOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'note'       => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->quantity) {
            return redirect()->route('stock.index')->with('error', 'الكمية المطلوبة أكبر من الكمية المتوفرة.');
        }

        $oldQty = $product->quantity;

        StockMovement::create([
            'product_id' => $product->id,
            'quantity'   => $request->quantity,
            'type'       => 'out',
            'note'       => $request->note,
        ]);

        $product->decrement('quantity', $request->quantity);

        // تسجيل الجرد
        StockAudit::create([
            'product_id' => $product->id,
            'old_quantity' => $oldQty,
            'new_quantity' => $product->quantity,
            'difference' => $product->quantity - $oldQty,
            'change_type' => 'خصم مخزون'
        ]);

        return redirect()->route('stock.index')->with('success', 'تم خصم الكمية من المخزون.');
    }
}
