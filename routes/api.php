<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ValidateJWT;
use App\Http\Middleware\VerifyEmailToken;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

// User account routes
Route::prefix('user')->group(function () {

    Route::get('', [UserController::class, 'show']);
    Route::put('', [UserController::class, 'update']);
    Route::delete('', [UserController::class, 'destroy']);
    Route::post('login', [UserController::class, 'login']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('register', [UserController::class, 'register']);
    Route::post('reset', [UserController::class, 'reset']);

});

// Admin account routes
Route::prefix('admin')->group(function () {

    Route::get('users', [AdminController::class, 'index']);

});

// Email verification route
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    if (!Auth::user()->hasVerifiedEmail()) {
        Auth::user()->markEmailAsVerified();

        event(new Verified(
            User::whereId(Auth::user()->getAuthIdentifier())
        ));
    }

    $url = config('app.url');

    return redirect($url);
})->middleware([VerifyEmailToken::class, 'signed'])->name('verification.verify');

Route::post('/email/re-verify', function () {
    if (Auth::user()->hasVerifiedEmail()) {
        return response()->json([
            "type" => "Email already verified",
            "message" => "Your email is already verified."
        ], 400);
    }

    Auth::user()->sendEmailVerificationNotification();

    return response()->json([
        "type" => "Successful request",
        "message" => "Verification link sent to your email."
    ]);
})->middleware([ValidateJWT::class, 'throttle:1,1'])->name('verification.send');

// Catch-all route
Route::fallback(function () {
    return response()->json([
        "type" => "Not found",
        "message" => "There's nothing here.."
    ], 404);
});
