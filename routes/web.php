<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController; 
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('materials', MaterialController::class);
Route::resource('products', ProductController::class);
Route::resource('productions', ProductionController::class);
Route::resource('orders', OrderController::class);
