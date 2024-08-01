<?php

use App\Http\Controllers\DayController;
use App\Http\Controllers\TripController;
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

// post APIs
Route::post('new-trip', [TripController::class, 'store']);
