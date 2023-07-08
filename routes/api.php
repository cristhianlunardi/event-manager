<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\ResetPasswordController;

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

Route::middleware(['PassportClientSecretProxy'])->post('/api-token', [ApiTokenController::class, 'issueToken']);

Route::prefix('users')->group(function()
{
    Route::post('register', [UserController::class, 'register']);
    Route::post('createUser', [UserController::class, 'createUser']);
    Route::post('createUserWithRole', [UserController::class, 'createUserWithRole']);
    Route::patch('role/{email}', [UserController::class, 'editRole']);

    Route::middleware(['auth', 'isValidUser'])->group(function()
    {

        Route::get('verify-token', [UserController::class, 'verifyToken']);
        Route::get('/', [UserController::class, 'getUsers']);
        Route::get('me', [UserController::class, 'selfUser']);
        Route::get('/me/permissions', [RoleController::class, 'getMyPermissions']);
        Route::delete('delete', [UserController::class, 'destroy']);
        Route::delete('delete/{targetEmail}', [UserController::class, 'destroyUser']);
        Route::put('update', [UserController::class, 'update']);
        Route::put('update/{targetEmail}', [UserController::class, 'updateUser']);
        Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    });
});

// These 'apiResources' are using => Route::middleware('auth') and Route::middleware('validUser') inside each constructor
// because we have to 'except' the Read/index endpoint, since everyone can ask this information
// Still need to find if there's a better approach
Route::get('eventType/getAllEventTypes', [EventTypeController::class, 'getAllEventTypes']);
Route::apiResources([
    'dependency' => DependencyController::class,
    'eventType' => EventTypeController::class,
    'roles' => RoleController::class,
    'event' => EventController::class,
]);

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    print($status);

    return $status === Password::PASSWORD_RESET
        ? "sucess" //redirect()->route('login')->with('status', __($status))
        : "bad - password didn't change"; //back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    $response = "";

    switch($status) {
        case Password::RESET_LINK_SENT:
            $response = "Correo enviado exitosamente.";
            break;

        case Password::PASSWORD_RESET:
            $response = "Reinicio de contraseña exitoso.";
            break;

        case Password::INVALID_USER:
            $response = "El correo ingresado no forma parte de nuestro registro de usuarios.";
            break;

        case Password::INVALID_TOKEN:
            $response = "Surgió un problema con la autenticación. Por favor contacte con un administrador.";
            break;

        case Password::RESET_THROTTLED:
            $response = "Por favor espere 60 segundos antes de realizar una nueva solicitud.";
            break;

        default:
            $response = "No fue posible establecer conexión con el servicio. Por favor contacte con un administrador.";
            break;
    }

    return $response;
})->middleware('guest')->name('password.email');

Route::get('/god-help-us', function (Request $request) {
    return $request;
    return "standard url";
})->middleware('guest')->name('password.reset');

