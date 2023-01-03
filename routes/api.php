<?php

use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => '/loja',
], function () {
    Route::apiResource('users', UsersController::class)->except('index');
    Route::apiResource('products', ProductsController::class)->except('index');
    Route::apiResource('orders', OrdersController::class)->except('index');
    Route::apiResource('stores', StoresController::class)->except('index');
});
