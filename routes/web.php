<?php

use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockAuditController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\ExpenseController;

/* Route::get('/', function () {
    return view('dashboard');
}); */


Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::resource('products', ProductController::class)->middleware('auth');
/* Route::resource('products', ProductController::class); */


Route::resource('categories', CategoryController::class)->middleware('auth');


Route::resource('brands', BrandController::class)->middleware('auth');



Route::get('/stock', [StockMovementController::class, 'index'])->name('stock.index');
Route::post('/stock/in', [StockMovementController::class, 'storeIn'])->name('stock.in');
Route::post('/stock/out', [StockMovementController::class, 'storeOut'])->name('stock.out');


Route::get('/pos', [POSController::class, 'index'])->name('pos.index')->middleware('auth');
Route::post('/pos/sell', [POSController::class, 'sell'])->name('pos.sell')->middleware('auth');



Route::get('/stock-audit', [StockAuditController::class, 'index'])->name('seals.index')->middleware('auth');



Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

Auth::routes();
