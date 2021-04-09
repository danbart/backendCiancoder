<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

// users routers
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/{id}', [UserController::class, 'show'])->where('id', '[0-9]+');


// role ruote
Route::get('/role', [RoleController::class, 'index']);
Route::post('/role/', [RoleController::class, 'store']);
Route::get('/role/{id}', [RoleController::class, 'show'])->where('id', '[0-9]+');
Route::put('/role/{id}', [RoleController::class, 'update'])->where('id', '[0-9]+');


// category route
Route::get('/category', [CategoryController::class, 'index']);
Route::get('/category/{id}', [CategoryController::class, 'show'])->where('id', '[0-9]+');

// product route
Route::get('/product', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show'])->where('id', '[0-9]+');


// sale route
Route::get('/sale', [SaleController::class, 'index']);
Route::get('/sale/{id}', [SaleController::class, 'show'])->where('id', '[0-9]+');
