<?php

use App\Http\Controllers\GifController;
use App\Http\Controllers\SecurityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [SecurityController::class, 'login']);

Route::prefix('gifs')->group(function () {
    Route::middleware('auth:api')->get('/search', [GifController::class, 'search']);
    Route::middleware('auth:api')->get('/{id}', [GifController::class, 'find']);
    Route::middleware('auth:api')->post('/{id}/save', [GifController::class, 'save']);
});
