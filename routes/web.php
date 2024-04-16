<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlantController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes([
    'register' => false
]);

Route::middleware('auth', 'admin')->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('plants', PlantController::class);
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/', DashboardController::class)->name('dashboard');
});
