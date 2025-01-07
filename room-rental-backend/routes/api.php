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
Route::get('districts', [LocationDistrictController::class, 'index']);
Route::get('wards', [LocationWardController::class, 'index']);

Route::post('register', [AuthController::class, 'register'])->name('user.register');
Route::post('login', [AuthController::class, 'login'])->name('user.login');

//Route::post('refresh_token', [AuthController::class, 'refreshToken'])->name('user.refresh_token');

Route::middleware(['auth:api','check.role:USER'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('user.logout');
    Route::get('profile', [AuthController::class, 'profile'])->name('user.profile');
    Route::post('/verify-email', [EmailVerifyController::class, 'verify']);

});

Route::post('/send-verification-email', [EmailVerifyController::class, 'sendVerificationEmail']);









