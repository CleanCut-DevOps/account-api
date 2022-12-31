<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ValidateJWT;
use App\Http\Middleware\ValidateUpdate;
use App\Models\user;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $user = Auth()->user();

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
        $authUser = Auth()->user();

        $user = User::find($authUser->id);

        if (!empty($request->contact)) $user->contact = $request->contact;
        if (empty($request->avatar)) $user->avatar = null; else $user->avatar = $request->avatar;

        if (!empty($request->email)) {
            $attempt = User::whereEmail($request->email)->get();

            if (count($attempt) == 0 || $attempt[0]->id == $user->id) {
                $user->email = $request->email;

            } else $errors["email"] = "Email already exists.";
        }

        if (!empty($request->username)) {
            $attempt = User::whereUsername($request->username)->get();

            if (count($attempt) == 0 || $attempt[0]->id == $user->id) {
                $user->username = $request->username;

            } else $errors["username"] = "Username already exists.";
        }

        $count = count($errors);

        if ($count == 0) {
            $user->save();

            return response()->json([
                "type" => "Successful request",
                "message" => "Account details updated successfully.",
                "account" => $user
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
