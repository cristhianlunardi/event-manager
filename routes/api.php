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
    
});

Route::group(['middleware' => 'lowercaseKey'], function () {
    Route::apiResources([
        
    ]);
});

Route::apiResources([
    'dependencies' => \App\Http\Controllers\DependencyController::class,
    'eventtypes' => \App\Http\Controllers\EventTypeController::class,
    'event' => \App\Http\Controllers\EventController::class,
]); 

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'user'
    ],
    function ()
    {
        Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
        Route::delete('delete', [\App\Http\Controllers\AuthController::class, 'deleteUsers']);
        Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
        Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
        Route::get('self', [\App\Http\Controllers\AuthController::class, 'selfUser']);
        Route::get('all', [\App\Http\Controllers\UserController::class, 'getUsers']);
        Route::post('update', [\App\Http\Controllers\AuthController::class, 'update']);
    }
);
