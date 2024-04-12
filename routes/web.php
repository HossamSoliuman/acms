<?php

use App\Http\Controllers\ExtractionController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\TableController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\Iterator\FilecontentFilterIterator;

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
// Route::get('t', function () {
//     $user = User::create([
//         'name' => 'Admin',
//         'email' => 'admin@gmail.com',
//         'email_verified_at' => now(),
//         'password' => Hash::make('password'),
//         'role' => 'admin',
//         'remember_token' => 'jklj;joijklnkn',
//     ]);
//     return $user;
// });
Route::middleware('auth')->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/', [HomeController::class, 'index'])->name('index');
});
