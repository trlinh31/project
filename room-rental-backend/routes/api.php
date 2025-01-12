<?php

use App\Http\Controllers\App\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Admin\LocationCityController;
use App\Http\Controllers\Admin\LocationDistrictController;
use App\Http\Controllers\Admin\LocationWardController;
use App\Http\Controllers\EmailVerifyController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\VnpayController;
use App\Http\Controllers\CommentController;
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



Route::get('/', function () {
    return response()->json(['status' => 'OK']);
});

Route::get('cities', [LocationCityController::class, 'index']);
Route::get('districts', [LocationCityController::class, 'getDistrict']);
Route::get('wards', [LocationDistrictController::class, 'getWard']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('/verify-email', [EmailVerifyController::class, 'verify']);
Route::post('/send-verification-email', [EmailVerifyController::class, 'sendVerificationEmail']);
Route::get('posts', [PostController::class, 'index']);
Route::get('profile', [AuthController::class, 'profile']);
Route::get('users', [AuthController::class, 'index']);
Route::post('payment', [VnpayController::class, 'index']);
Route::post('payment-return', [VnpayController::class, 'vnpayReturn']);
Route::post('posts/search', [PostController::class, 'search']);
Route::post('/comment', [CommentController::class, 'create']);
Route::put('/comment', [CommentController::class, 'update']);
Route::delete('/comment', [CommentController::class, 'delete']);
Route::get('/comment/{id}', [CommentController::class, 'index']);
