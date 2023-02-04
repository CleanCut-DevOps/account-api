<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\VerifyEmailToken;
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
    if (!$request->user()->hasVerifiedEmail()) {
        $request->user()->markEmailAsVerified();

        event(new Verified($request->user()));
    }

    $url = config('app.url');

    return redirect($url);
})->middleware([VerifyEmailToken::class, 'signed'])->name('verification.verify');

// Catch-all route
Route::fallback(function () {
    return response()->json([
        "type" => "Not found",
        "message" => "There's nothing here.."
    ], 404);
});
