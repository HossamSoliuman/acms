<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PlantController;
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

Route::post('login', [AuthenticationController::class, 'login']);
Route::post('register', [AuthenticationController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('user', [AuthenticationController::class, 'user']);
        Route::post('logout', [AuthenticationController::class, 'logout']);
        Route::post('update', [AuthenticationController::class, 'update']);
    });
});
Route::get('plants', [PlantController::class, 'apiIndex']);
