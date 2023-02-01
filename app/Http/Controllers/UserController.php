<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ValidateJWT;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     * Set middleware for all functions
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(ValidateJWT::class)->except(["login", "register"]);
    }

    /**
     * Login user and create token
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $emailCred = $request->only("email", "password");

        if ($token = Auth::setTTL(request("remember") ? 60 * 24 * 7 : 60 * 24)->attempt($emailCred)) {
            return response()->json([
                "type" => "Successful request",
                "message" => "Logged in successfully",
                "token" => $token
            ]);
        } else {
            return response()->json([
                "type" => "Unauthorized",
                "message" => "Invalid credentials"
            ], 401);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $request["password"] = Hash::make(request("password"));

        $user = User::create($request->all());

        event(new Registered($user));

        return response()->json([
            "type" => "Successful request",
            "message" => "User created successfully"
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            "type" => "Successful request",
            "message" => "User logged out successfully"
        ], 200);
    }

    /**
     * Reset the user's password.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $userID = Auth::payload()->get("sub");

        $user = User::find($userID);

        if (Hash::check(request("oldPassword"), $user->password)) {
            $user->password = Hash::make(request("newPassword"));
            $user->save();

            return response()->json([
                "type" => "Successful request",
                "message" => "Password changed successfully"
            ], 200);
        } else {
            return response()->json([
                "type" => "Invalid data",
                "message" => "Request data is invalid"
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            "type" => "Successful request",
            "message" => "User data retrieved successfully",
            "user" => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $user->update($request->only(['email', 'phone', 'full_name', 'preferred_contact']));

        return response()->json([
            "type" => "Successful request",
            "message" => "User data updated successfully",
            "user" => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        Auth::logout();

        $user->delete();

        return response()->json([
            "type" => "Successful request",
            "message" => "User data deleted successfully"
        ]);
    }
}
