<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HandsController;
use App\Http\Controllers\PlayerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/hands', [HandsController::class, 'upload']);

Route::apiResource('player', 'PlayerController')->middleware('auth:sanctum');
