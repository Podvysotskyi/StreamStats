<?php

use App\Http\Controllers\AuthControlller;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\StreamsController;
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

Route::middleware(['auth:sanctum', 'expire_session'])->group(function() {
    Route::get('/user', [AuthControlller::class, 'user']);
});

Route::get('/games/top', [GamesController::class, 'top']);