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
use App\Http\Controllers\UserDetailController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CustomerDebtController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\PersonalDebtController;

/* Route::get('/', function () {
    return view('dashboard');
}); */


Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::resource('products', ProductController::class)->middleware('auth');
/* Route::resource('products', ProductController::class); */


Route::resource('categories', CategoryController::class)->middleware('auth');


Route::resource('brands', BrandController::class)->middleware('auth');



// routes/web.php


Route::prefix('stock')->group(function () {
    Route::get('/',        [StockMovementController::class, 'index'])->name('stock.index');
    Route::get('/edit',    [StockMovementController::class, 'edit'])->name('stock.edit');
    Route::post('/transfer', [StockMovementController::class, 'transfer'])->name('stock.transfer');
});



Route::get('/pos', [POSController::class, 'index'])->name('pos.index')->middleware('auth');
Route::post('/pos/sell', [POSController::class, 'sell'])->name('pos.sell')->middleware('auth');



Route::get('/stock-audit', [StockAuditController::class, 'index'])->name('seals.index')->middleware('auth');



Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');





Route::get('/users', [UserDetailController::class, 'index'])->name('users.index');
Route::post('/users', [UserDetailController::class, 'store'])->name('users.store');
Route::put('/users/{user}', [UserDetailController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserDetailController::class, 'destroy'])->name('users.destroy');



Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');




Route::prefix('personal-debts')->group(function () {
    Route::get('/', [PersonalDebtController::class, 'index'])->name('personal-debts.index');         // عرض كل المديونيات
    Route::get('/create', [PersonalDebtController::class, 'create'])->name('personal-debts.create'); // صفحة الإنشاء
    Route::post('/', [PersonalDebtController::class, 'store'])->name('personal-debts.store');        // حفظ المديونية
    Route::get('/{id}/edit', [PersonalDebtController::class, 'edit'])->name('personal-debts.edit');  // تعديل مديونية
    Route::put('/{id}', [PersonalDebtController::class, 'update'])->name('personal-debts.update');   // تحديث المديونية
    Route::delete('/{id}', [PersonalDebtController::class, 'destroy'])->name('personal-debts.destroy'); // حذف مديونية
});


Route::prefix('customer-debts')->group(function () {
    // إن كنت هتجيب فورم المودال جاهز من السيرفر (اختياري)
    Route::get('{sale}/edit', [CustomerDebtController::class, 'edit'])->name('customer-debts.edit');

    // تحديث المدفوع
    Route::put('{sale}', [CustomerDebtController::class, 'update'])->name('customer-debts.update');
});

Route::post('/pos/return-sale', [POSController::class, 'returnSale'])
    ->name('pos.return');
Auth::routes();
