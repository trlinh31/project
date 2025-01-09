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

//Route::post('refresh_token', [AuthController::class, 'refreshToken'])->name('admin.refresh_token');
//Route::get('active-user/{token}', [AuthController::class, 'activeAccount']);

Route::get('posts/{id}', [PostController::class, 'show']);
Route::get('posts', [PostController::class, 'index'])->name('posts.get');


Route::middleware(['auth:api_admin','check.role:ADMIN'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::get('profile', [AuthController::class, 'profile'])->name('admin.profile');

    Route::post('posts', [PostController::class, 'store'])->name('posts.create');
    Route::put('posts/{id}', [PostController::class, 'update'])->name('post.update');
    Route::delete('posts/{id}', [PostController::class, 'destroy']);

    Route::get('users', [AuthController::class, 'index']);
    Route::get('users/{id}', [AuthController::class, 'show']);
    Route::put('users/{id}', [AuthController::class, 'update']);
    Route::delete('users/{id}', [AuthController::class, 'deleteUser']);

    Route::get('posts/save-favorite/{id}', [PostController::class, 'saveFavorite']);
    Route::get('posts/favorites/all', [PostController::class, 'getFavoritePost']);
    Route::delete('posts/delete-favorite/{id}', [PostController::class, 'deleteFavorite']);
});


