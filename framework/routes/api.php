<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::any('product/list',  [\App\Http\Controllers\ProductController::class, 'index']);
    Route::any('customer/list',  [\App\Http\Controllers\UserController::class, 'index']);
    Route::resource('order', \App\Http\Controllers\OrderController::class);
});


Route::post('auth/login/',  [\App\Http\Controllers\AuthController::class, 'login']);
Route::any('product/update/{id}',  [\App\Http\Controllers\ProductController::class, 'update']);
Route::any('order/discounts/{id}',  [\App\Http\Controllers\DiscountController::class, 'index']);
Route::any('login',  [\App\Http\Controllers\AuthController::class, 'rlogin'])->name('login');;
