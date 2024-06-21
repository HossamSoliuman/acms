<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EngController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawalRequestController;
use App\Serveces\MeetingServece;
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

Route::middleware(['auth:sanctum', 'eng'])->prefix('eng')->group(function () {
    Route::get('meetings', [EngController::class, 'getUpcomingMeetings']);
    Route::get('available-times', [EngController::class, 'getAvailableTimes']);
    Route::post('meetings', [EngController::class, 'setAvailableTime']);
    Route::post('withdrawal-requests', [WithdrawalRequestController::class, 'store']);
    Route::get('withdrawal-requests', [WithdrawalRequestController::class, 'getWithdrawalRequests']);
    Route::put('withdrawal-requests', [WithdrawalRequestController::class, 'cancelWithdrawalRequest']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('user', [AuthenticationController::class, 'user']);
        Route::post('logout', [AuthenticationController::class, 'logout']);
        Route::post('update', [AuthenticationController::class, 'update']);
    });
    Route::prefix('users')->group(function () {
        Route::get('orders', [UserController::class, 'orders']);
        Route::get('upcoming-meetings', [UserController::class, 'getUpcomingMeetings']);
        Route::put('meetings/{meeting}', [UserController::class, 'setMeeting']);
        Route::get('engs', [UserController::class, 'getEngs']);
        Route::get('engs/{user}/available-times', [UserController::class, 'engAvailableTimes']);
        Route::get('create-meeting/{meeting}', [MeetingController::class, 'store']);
        Route::post('meetings/add-review', [MeetingController::class, 'addReview']);
    });
    Route::post('stripe-checkout', [CheckoutController::class, 'checkout']);
    Route::get('paypal-transaction', [PayPalController::class, 'processTransaction'])->name('processTransaction');
});
Route::get('plants', [PlantController::class, 'apiIndex']);
Route::get('products', [ProductController::class, 'apiIndex']);

Route::get('/checkout-cancel', function () {
    return 'canceled';
})->name('checkout-cancel');

Route::get('/checkout-success', [CheckoutController::class, 'checkoutSuccess'])->name('checkout-success');

Route::get('success-transaction', [PayPalController::class, 'successTransaction'])->name('successTransaction');
Route::get('cancel-transaction', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');
Route::get('mettings/checkout-success', [MeetingController::class, 'checkoutSuccess'])->name('meetings.checkout-success');
