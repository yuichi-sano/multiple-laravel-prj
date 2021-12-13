<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccessTokenGetController;
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
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::post('/login', [AuthController::class, 'login']);
Route::get('/access_token_get', [AccessTokenGetController::class,'index']);
Route::group(['middleware' => 'api_for_web'], function () {
    Route::get('/sample', [SampleController::class, 'index']);
});

