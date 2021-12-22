<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\AccessTokenGetController;
use App\Http\Controllers\Sample\SampleController;

//artisanUseAddPoint
/** ↑は自動生成に必要です。消さないよう注意ください */
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
Route::post('/login', [LoginController::class, 'login']);
Route::post('/refresh', [AccessTokenGetController::class,'index']);

Route::group(['middleware' => 'tokenAuth'], function () {
    Route::get('/sample', [SampleController::class,'index']);
});

//artisanRouteAddPoint
/** ↑は自動生成に必要です。消さないよう注意ください */

