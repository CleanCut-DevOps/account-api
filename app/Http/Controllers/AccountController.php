<?php

namespace App\Http\Controllers;

use App\Models\user;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Auth;

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
        $this->middleware('auth:api');
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function read(): JsonResponse
    {
        $user = Auth()->user();

        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  User  $user
     * @return JSONResponse
     */
    public function update(Request $request, User $user): JSONResponse
    {
        $request->validate([
            'avatar' => ['nullable', 'string'],
            'username' => ['nullable', 'string', 'max:48', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'description' => ['nullable', 'string'],
        ]);

        $authUser = Auth()->user();

        $user = User::find($authUser->id);

        if (!empty($request->username)) $user->username = $request->username;
        if (!empty($request->contact)) $user->contact = $request->contact;
        if (!empty($request->avatar)) $user->avatar = $request->avatar;
        if (!empty($request->email)) $user->email = $request->email;

        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'token' => $user->getJWTIdentifier(),
            'account' => $user
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  user  $user
     * @return JSONResponse
     */
    public function delete(user $user): JsonResponse
    {
        return response()->json(['message' => 'TODO: Method to delete user profile'], 404);
    }
}
