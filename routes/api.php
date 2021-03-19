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

Route::prefix('users')->group(function()
{
    Route::post('register', [UserController::class, 'register']);

    Route::middleware(['auth', 'validUser'])->group(function()
    {
        Route::get('/', [UserController::class, 'getUsers']);
        Route::get('me', [UserController::class, 'selfUser']);
        Route::delete('delete', [UserController::class, 'destroyUser']);
        Route::put('update', [UserController::class, 'updateUser']);
        Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    });
});

// These 'apiResources' are using => Route::middleware('auth') and Route::middleware('validUser') inside each constructor
// because we have to 'except' the Read/index endpoint, since everyone can ask this information
// Still need to find if there's a better approach
Route::apiResources([
    'dependencies' => DependencyController::class
    //'eventTypes' => EventTypeController::class,
    //'event' => EventController::class,
]);

