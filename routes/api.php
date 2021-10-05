<?php

use App\Http\Controllers\PlaceController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Response;
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
    Route::prefix('/auth')->group(function () {
        Route::post('/login', [UserController::class, 'login']);
        Route::get('/logout', [UserController::class, 'logout']);
    });

    Route::prefix('/place')->group(function () {
        Route::get('/', [PlaceController::class, 'index']);
        Route::post('', [PlaceController::class, 'store']);
        Route::get('/{place}', [PlaceController::class, 'show']);
        Route::delete('/{place}', [PlaceController::class, 'destroy'])
            ->missing(function () {
                return  response([
                    'message' => 'Data cannot be deleted'
                ], 400);
            });
        Route::put('/{place}', [PlaceController::class, 'update'])
            ->missing(function () {
                return  response([
                    'message' => 'Data cannot be updated'
                ], 400);
            });
    });

    Route::prefix('/schedule')->group(function () {
        Route::post('', [ScheduleController::class, 'store']);
        Route::delete('/{schedule}', [ScheduleController::class, 'destroy'])
            ->missing(function () {
                return  response([
                    'message' => 'Data cannot be deleted'
                ], 400);
            });
    });

    Route::prefix('route')->group(function () {
        Route::get('/search/{fromPlace}/{toPlace}/{depTime}', [RouteController::class, 'search']);
        Route::post('/selection', [RouteController::class, 'selection']);
    });
});
