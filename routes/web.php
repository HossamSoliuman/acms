<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayPalController;

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

Auth::routes([
    'register' => false
]);

Route::middleware('auth', 'admin')->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::put('/products/{product}', 'ProductController@update')->name('products.update');
    Route::get('/orders/{order}/export', [OrderController::class, 'export'])->name('orders.export');
    Route::resource('orders', OrderController::class);

    Route::resource('plants', PlantController::class);
    Route::resource('products', ProductController::class);
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/', DashboardController::class)->name('dashboard');
});
