<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\LocationCityController;
use App\Http\Controllers\Admin\LocationDistrictController;
use App\Http\Controllers\Admin\LocationWardController;
use App\Http\Controllers\Admin\PostController;
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

Route::get('/', function () {
    return response()->json(['status' => 'ChienTT OK']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'create']);
Route::get('posts/{id}', [PostController::class, 'show']);
Route::get('posts', [PostController::class, 'index']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('profile', [AuthController::class, 'profile']);
Route::post('posts', [PostController::class, 'store']);
Route::put('posts/{id}', [PostController::class, 'update']);
Route::delete('posts/{id}', [PostController::class, 'destroy']);
Route::put('posts/status/{id}', [PostController::class, 'changeStatus']);
Route::put('posts/verify/{id}', [PostController::class, 'verifyPost']);
Route::get('users', [AuthController::class, 'index']);
Route::get('users/{id}', [AuthController::class, 'show']);
Route::put('users/{id}', [AuthController::class, 'update']);
Route::delete('users/{id}', [AuthController::class, 'deleteUser']);
Route::put('users/password/{id}', [AuthController::class, 'changePassword']);
Route::get('posts/save-favorite/{id}', [PostController::class, 'saveFavorite']);
Route::get('posts/favorites/all', [PostController::class, 'getFavoritePost']);