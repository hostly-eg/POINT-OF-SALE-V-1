<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Car;
use App\Models\StockAudit;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()

    {
        $products = Product::with(['category', 'brand'])->latest()->get();
        $categories = Category::all();
        $brands = Brand::all();

        return view('products.index', compact('products', 'categories', 'brands'));
    }
    /* index */




    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $cars = Car::all();

        return view('products.create', compact('categories', 'brands', 'cars'));
    }
    /* create */



    public function show($id)
    {
        $products = Product::with(['category', 'brand', 'car'])->latest()->get();
        $categories = Category::all();
        $brands = Brand::all();
        return view('products.index', compact('products', 'categories', 'brands'));
    }
    /* show */



    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'brand_id'       => 'required|exists:brands,id',
            'category_id'    => 'required|exists:categories,id',
            'car_id'         => 'nullable|exists:cars,id',
            'price'          => 'required|numeric|min:0',
            'profit_margin'  => 'nullable|numeric|min:0',
            'quantity_shop'  => 'required|integer|min:0',
            'quantity_store' => 'required|integer|min:0',
            'image'          => 'nullable|image',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // الكميات الابتدائية جاية من الفورم
        $shopInit  = (int) $request->quantity_shop;
        $storeInit = (int) $request->quantity_store;

        $product = Product::create([
            'name'           => $request->name,
            'brand_id'       => $request->brand_id,
            'category_id'    => $request->category_id,
            'car_id'         => $request->car_id,
            'price'          => $request->price,
            'profit_margin'  => $request->profit_margin,
            'quantity_shop'  => $shopInit,
            'quantity_store' => $storeInit,
            'image'          => $imagePath,
        ]);

        // القيم القديمة قبل الإنشاء (كلها صفر)
        $shopOld  = 0;
        $storeOld = 0;

        // القيم الجديدة بعد الإنشاء
        $shopNew  = $shopInit;
        $storeNew = $storeInit;

        // لو عايز تسجل "حركة واحدة" تلخص إنشاء المنتج بالكامل:
        StockMovement::create([
            'product_id'    => $product->id,
            'quantity'      => $shopInit + $storeInit, // إجمالي ما تم إدخاله
            'type'          => 'init',                  // أو اعملها init_shop + init_store كحركتين (انظر أسفل)
            'note'          => 'إضافة أولية للمحل والمخزن',
            'shop_qty_old'  => $shopOld,
            'shop_qty_new'  => $shopNew,
            'store_qty_old' => $storeOld,
            'store_qty_new' => $storeNew,
        ]);

        // لو تفضّل "حركتين منفصلتين" بدل واحدة:
        /*
    if ($shopInit > 0) {
        StockMovement::create([
            'product_id'    => $product->id,
            'quantity'      => $shopInit,
            'type'          => 'init_shop',   // <-- طابق ENUM بتاعك
            'note'          => 'إضافة أولية للمحل',
            'shop_qty_old'  => 0,
            'shop_qty_new'  => $shopInit,
            'store_qty_old' => 0,
            'store_qty_new' => $storeInit,    // حالة المنتج بعد الإنشاء
        ]);
    }

    if ($storeInit > 0) {
        StockMovement::create([
            'product_id'    => $product->id,
            'quantity'      => $storeInit,
            'type'          => 'init_store',  // <-- مش init_warehouse
            'note'          => 'إضافة أولية للمخزن',
            'shop_qty_old'  => $shopInit,     // حالة المحل بعد الإنشاء
            'shop_qty_new'  => $shopInit,
            'store_qty_old' => 0,
            'store_qty_new' => $storeInit,
        ]);
    }
    */

        // جرد/لوج (اختياري)
        StockAudit::create([
            'product_id'    => $product->id,
            'old_shop_qty'  => 0,
            'new_shop_qty'  => (int) $product->quantity_shop,
            'old_store_qty' => 0,
            'new_store_qty' => (int) $product->quantity_store,
            'change_type'   => 'إنشاء منتج',
        ]);
        return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }


    /* store */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        $cars = Car::all();
        return view('products.edit', compact('product', 'categories', 'brands', 'cars'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:255',
            'brand_id'       => 'required|exists:brands,id',
            'category_id'    => 'required|exists:categories,id',
            'car_id'         => 'nullable|exists:cars,id',
            'price'          => 'required|numeric|min:0',
            'profit_margin'  => 'nullable|numeric|min:0',
            'quantity_shop'  => 'required|integer|min:0',
            'quantity_store' => 'required|integer|min:0',
            'image'          => 'nullable|image',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name'           => $request->name,
            'brand_id'       => $request->brand_id,
            'category_id'    => $request->category_id,
            'car_id'         => $request->car_id, // أو car حسب الفورم
            'price'          => $request->price,
            'profit_margin'  => $request->profit_margin,
            'quantity_shop'  => $request->quantity_shop,   // من الفورم
            'quantity_store' => $request->quantity_store,  // من الفورم
            'image'          => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'تم حذف المنتج');
    }
}
