<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    $files = array_diff(scandir( app_path("Helpers/Discounts/")), array('.', '..'));

    /*$products = \App\Models\OrderItem::where('order_id',7)->get();
    $order    = \App\Models\Order::find('id');
    $response = app()->make('discount')->calculate($products,$order->total);*/
    return view('welcome');
});
