<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HandsController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/hands', [HandsController::class, 'upload']);

Route::get('/statistics', [StatisticsController::class, 'index']);

Route::get('/best-hands', [StatisticsController::class, 'bestHands']);

Route::apiResource('player', 'PlayersController')->middleware('auth:sanctum');
