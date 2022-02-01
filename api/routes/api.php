<?php

use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['XssSanitization']], function () {
    Route::get('/products', [ProductsController::class, 'filter']);
    Route::post('/products', [ProductsController::class, 'create']);
    Route::delete('/products/{id}', [ProductsController::class, 'delete']);
    Route::post('/products/{id}', [ProductsController::class, 'edit']);
    Route::get('/report', [ProductsController::class, 'report']);
});
