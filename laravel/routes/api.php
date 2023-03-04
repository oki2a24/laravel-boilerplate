<?php

use App\Http\Controllers\HealthController;
use Illuminate\Http\Request;
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

Route::get('/health', [HealthController::class, 'health'])->name('health');
Route::get('/health/deep', [HealthController::class, 'healthDeep'])->name('health.deep');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
