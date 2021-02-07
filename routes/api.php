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

Route::apiResources([
    'dependencies' => \App\Http\Controllers\DependencyController::class,
    'eventtypes' => \App\Http\Controllers\EventTypeController::class,
]);

Route::delete('/dependencies/delete-many', [\App\Http\Controllers\DependencyController::class, 'destroyMany']);

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'user'
    ],
    function ()
    {
        Route::post('login', [\App\Http\Controllers\JwtAuthController::class, 'login']);
        Route::post('register', [\App\Http\Controllers\JwtAuthController::class, 'register']);
        Route::post('logout', [\App\Http\Controllers\JwtAuthController::class, 'logout']);
        Route::post('user-info', [\App\Http\Controllers\JwtAuthController::class, 'getUser']);
        Route::post('refresh', [\App\Http\Controllers\JwtAuthController::class, 'refresh']);
        Route::post('me', [\App\Http\Controllers\JwtAuthController::class, 'me']);
        Route::get('all', [\App\Http\Controllers\JwtAuthController::class, 'all']);
    }
);
