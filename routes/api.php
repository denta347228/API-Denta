<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::resource('user', UserController::class);
Route::resource('categories', CategoriesController::class);
Route::resource('order', OrderController::class);
Route::resource('product', ProductController::class);


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

route::group(['middleware' => ['api']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('me', [AuthController::class, 'me']);

});
