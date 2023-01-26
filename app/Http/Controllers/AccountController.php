<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ValidateJWT;
use App\Http\Middleware\ValidateUpdate;
use App\Models\user;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Create a new UserController instance.
     * Set middleware for all functions
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(ValidateJWT::class);
        $this->middleware(ValidateUpdate::class)->only("update");
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function read(): JsonResponse
    {
        $userID = Auth::payload()->get("sub");

        $user = User::find($userID);

        return response()->json([
            "type" => "Successful request",
            "message" => "Account details retrieved successfully",
            "account" => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function update(Request $request): JSONResponse
    {
        $errors = [];
        $userID = Auth::payload()->get("sub");

        $user = User::whereId($userID)->first();

        if (!empty(request("full_name")) && $user->full_name != request("full_name")) $user->full_name = request("full_name");
        if (!empty(request("phone_number")) && $user->phone_number != request("phone_number")) $user->phone_number = request("phone_number");
        if (empty(request("avatar"))) $user->avatar = null; else $user->avatar = request("avatar");

        if (!empty(request("email")) && $user->email != request("email")) {
            $attempt = User::whereEmail(request("email"))->get();

            if (count($attempt) == 0) {
                $user->email = request("email");
            } else $errors["email"] = "Email already exists.";
        }

        $count = count($errors);

        if ($count == 0) {
            $user->save();

            return response()->json([
                "type" => "Successful request",
                "message" => "Account details updated successfully.",
                "account" => $user->refresh()
            ]);

        } else if ($count == 1) {
            return response()->json([
                "type" => "Invalid data",
                "message" => array_values($errors)[0],
                "errors" => $errors,
            ], 400);
        } else if ($count == 2) {
            return response()->json([
                "type" => "Invalid data",
                "message" => array_values($errors)[0] . " (and 1 more error.)",
                "errors" => $errors,
            ], 400);
        } else {
            return response()->json([
                "type" => "Invalid data",
                "message" => array_values($errors)[0] . " (and " . count($errors) - 1 . " more errors.)",
                "errors" => $errors,
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return JSONResponse
     */
    public function delete(): JsonResponse
    {
        $authUser = Auth()->user();

        $user = User::find($authUser->id);

        Auth()->logout();

        $user->delete();

        return response()->json([
            "type" => "Successful request",
            "message" => "Account deleted successfully"
        ]);
    }
}
