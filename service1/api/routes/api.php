<?php

use App\Http\Controllers\Device\WorkplaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\AccessTokenGetController;
use App\Http\Controllers\Authentication\HealthCheckController;
use App\Http\Controllers\Yusei\ZipCodeYuseiLastUpdateController;
use App\Http\Controllers\Yusei\ZipCodeKenAllController;
use App\Http\Controllers\Yusei\ZipCodeYuseiBulkController;
use App\Http\Controllers\Yusei\ZipCodeYuseiSearchController;
use App\Http\Controllers\Yusei\ZipCodeYuseiController;
use App\Http\Controllers\Yusei\PrefectureController;
use \App\Http\Controllers\Device\DeviceController;

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
Route::get('/health_check', [HealthCheckController::class,'index']);

Route::group(['middleware' => 'tokenAuth'], function () {
    Route::get('/zip_code/yusei/last_update', [ZipCodeYuseiLastUpdateController::class, 'index']);
    Route::get('/zip_code/ken_all', [ZipCodekenAllController::class, 'index']);
    Route::put('/zip_code/yusei/bulk', [ZipCodeyuseiBulkController::class, 'update']);
    Route::get('/zip_code/yusei/search', [ZipCodeYuseiSearchController::class, 'index']);
    Route::post('/zip_code/yusei', [ZipCodeYuseiController::class, 'store']);
    Route::put('/zip_code/yusei/{id}', [ZipCodeYuseiController::class, 'update']);
    Route::delete('/zip_code/yusei/{id}', [ZipCodeYuseiController::class, 'destroy']);
    Route::get('/prefecture', [PrefectureController::class, 'index']);
    Route::get('/workplace', [WorkplaceController::class,'index']);
    Route::get('/device', [DeviceController::class,'index']);
    Route::post('/device', [DeviceController::class,'store']);
    Route::put('/device/{id}', [DeviceController::class,'update']);
    Route::get('/device/{id}', [DeviceController::class,'detail']);
});


//artisanRouteAddPoint
/** ↑は自動生成に必要です。消さないよう注意ください */

