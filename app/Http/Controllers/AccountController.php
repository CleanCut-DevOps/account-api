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
        $authUser = Auth()->user();

        $user = User::find($authUser->id);

        if (!empty($request->username)) $user->username = $request->username;
        if (!empty($request->contact)) $user->contact = $request->contact;
        if (!empty($request->avatar)) $user->avatar = $request->avatar;
        if (!empty($request->email)) $user->email = $request->email;

        $user->save();

        return response()->json([
            "type" => "Successful request",
            "message" => "Account details updated successfully",
            "account" => $user
        ]);
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
