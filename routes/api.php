<?php

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

Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout']);

Route::prefix('users')->group(function()
{
    Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::get('/', [\App\Http\Controllers\UserController::class, 'getUsers']);

    Route::middleware('auth')->group(function()
    {
        Route::get('me', [\App\Http\Controllers\AuthController::class, 'selfUser']);
        Route::delete('delete', [\App\Http\Controllers\AuthController::class, 'deleteUsers']);
        Route::post('update', [\App\Http\Controllers\AuthController::class, 'update']);
    });
});

Route::middleware('auth')->group(function()
{
    Route::apiResources([
        'dependencies' => \App\Http\Controllers\DependencyController::class,
        'eventTypes' => \App\Http\Controllers\EventTypeController::class,
        'event' => \App\Http\Controllers\EventController::class,
    ]);
});
