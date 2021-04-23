<?php

namespace App\Http\Controllers\ApiController;

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

Route::prefix('/faraidh')->group(function(){
    Route::get(NULL, [FaraidhController::class, 'index']);
    Route::match(['get', 'post'], 'load/{param?}', [FaraidhController::class, 'load']);
});
