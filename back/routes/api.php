<?php

use App\Http\Controllers\TripController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\StopController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// get APIs
Route::get('trips', [TripController::class, 'index']);
Route::get('trips/{trip_id}', [TripController::class, 'show']);

Route::get('day{day:id}', [DayController::class, 'show']);

Route::get('stop{stop:id}', [StopController::class, 'show']);

// post APIs
Route::post('new-trip', [TripController::class, 'store']);
Route::post('update-trip/{trip_id}', [TripController::class, 'update']);
Route::post('delete-trip/{trip_id}', [TripController::class, 'destroy']);

Route::post('new-day', [DayController::class, 'store']);
Route::post('update-day/{day_id}', [DayController::class, 'update']);
Route::post('delete-day/{day_id}', [DayController::class, 'destroy']);

Route::post('new-stop', [StopController::class, 'store']);
Route::post('update-stop/{stop_id}', [StopController::class, 'update']);
Route::post('delete-stop/{stop_id}', [StopController::class, 'destroy']);
