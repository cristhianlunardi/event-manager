<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('dependencies', \App\Http\Controllers\DependencyController::class);

Route::post('login', [\App\Http\Controllers\JwtAuthController::class, 'login']);
Route::post('register', [\App\Http\Controllers\JwtAuthController::class, 'register']);

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', [\App\Http\Controllers\JwtAuthController::class, 'logout']);
    Route::get('user-info', [\App\Http\Controllers\JwtAuthController::class, 'getUser']);
});