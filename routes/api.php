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

Route::group(['middleware'=>'auth:api'], function() {
    Route::post('testOauth', [\App\Http\Controllers\AuthController::class, 'testOauth']);
    Route::get('getUsers', [\App\Http\Controllers\UserController::class, 'getUsers']);
});

Route::apiResources([
    'dependencies' => \App\Http\Controllers\DependencyController::class,
    'eventtypes' => \App\Http\Controllers\EventTypeController::class,
]); 

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'user'
    ],
    function ()
    {
        Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
        Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
        Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
        Route::post('user-info', [\App\Http\Controllers\AuthController::class, 'getUser']);
        Route::post('refresh', [\App\Http\Controllers\AuthController::class, 'refresh']);
        Route::post('me', [\App\Http\Controllers\AuthController::class, 'me']);
        Route::get('all', [\App\Http\Controllers\AuthController::class, 'all']);
    }
);
