<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WeightController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



/*
|--------------------------------------------------------------------------
| Authentication Routes (Mobile Verification Based)
|--------------------------------------------------------------------------
|
*/
Route::prefix('auth')->group(function () {
    Route::post('/check-mobile', [AuthController::class, 'checkMobile']);
    Route::post('/check-code', [AuthController::class, 'checkVerifyCode']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login-password', [AuthController::class, 'loginWithPassword']);
    Route::post('/resend-code', [AuthController::class, 'resendCode']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/set-password', [AuthController::class, 'setPassword']);
});



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/weights', [WeightController::class, 'storeOrUpdate']);
});
