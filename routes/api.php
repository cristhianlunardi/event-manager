<?php

namespace App\Http\Controllers;

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

// Route 'login' is 'oauth/token' from passport package. 'grant_type' => 'password' must be used.
// Route 'refresh_token' is 'oauth/token' from passport package. 'grant_type' => 'refresh_token' must be used.
Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout']);

Route::prefix('users')->group(function()
{
    Route::post('register', [UserController::class, 'register']);

    Route::middleware(['auth', 'validUser'])->group(function()
    {
        Route::get('/', [UserController::class, 'getUsers']);
        Route::get('me', [UserController::class, 'selfUser']);
        Route::delete('delete', [UserController::class, 'destroyUser']);
        Route::post('update', [UserController::class, 'updateUser']);
    });
});

// These 'apiResources' are using => Route::middleware('auth') inside each constructor
// Still need to find if there's a better approach
Route::apiResources([
    'dependencies' => DependencyController::class,
    'eventTypes' => EventTypeController::class,
    'event' => EventController::class,
]);
