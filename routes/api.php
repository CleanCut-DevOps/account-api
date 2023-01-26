<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthenticationController;
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

Route::get('/', function () {
    return response()->json([
        "type" => "Not found",
        "message" => "There's nothing here..",
    ], 404);
});

// Authentication
Route::post('login', [AuthenticationController::class, 'login']);
Route::post('register', [AuthenticationController::class, 'register']);
Route::post('logout', [AuthenticationController::class, 'logout']);
Route::post('reset', [AuthenticationController::class, 'resetPassword']);

// User Account
Route::get('account', [AccountController::class, 'read']);
Route::put('account', [AccountController::class, 'update']);
Route::delete('account', [AccountController::class, 'delete']);
