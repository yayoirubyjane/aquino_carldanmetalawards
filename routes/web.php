<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('orders.index');
});

Route::resource('materials', MaterialController::class)->except('show');
Route::resource('products', ProductController::class);
Route::resource('clients', ClientController::class)->except('show');
Route::resource('suppliers', SupplierController::class)->except('show');
Route::resource('stocks', StockController::class)->except('show');
Route::resource('orders', OrderController::class)->except('show');
Route::resource('productions', ProductionController::class)->except(['show', 'create', 'store']);
Route::resource('payments', PaymentController::class)->except('show');
