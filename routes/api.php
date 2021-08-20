<?php

use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
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


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [UserController::class, 'login']);
        Route::middleware('ua')->get('/logout', [UserController::class, 'logout']);
    });

    Route::prefix('place')->group(function () {
        Route::get('/', [PlaceController::class, 'index']);
        Route::get('/{place}', [PlaceController::class, 'show']);
        Route::post('?token={token}', [PlaceController::class, 'store']);
        Route::delete('/{place}?token={token}', [PlaceController::class, 'destory']);
        Route::post('/{place}?token={token}', [PlaceController::class, 'update']);
    });

    Route::prefix('schedule')->group(function () {
        Route::post('?token={token}', [ScheduleController::class, 'store']);
        Route::delete('/{schedule}?token={token}', [ScheduleController::class, 'destory']);
    });
});
