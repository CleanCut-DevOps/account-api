<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ValidateJWT;
use App\Http\Middleware\ValidateLogin;
use App\Http\Middleware\ValidateRegister;
use App\Http\Middleware\ValidateReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{

    /**
     * Create a new AuthController instance.
     * Set middleware for all functions
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(ValidateJWT::class, ["except" => ["login", "register"]]);
        $this->middleware(ValidateRegister::class)->only("register");
        $this->middleware(ValidateLogin::class)->only("login");
        $this->middleware(ValidateReset::class)->only("resetPassword");
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
        $userCred = $request->only("username", "password");

        if ($token = Auth()->setTTL($request->stay ? 10800 : 3600)->attempt($emailCred)) {
            return response()->json([
                "type" => "Successful request",
                "message" => "User logged in successfully",
                "token" => $token,
            ], 200);
        } else if ($token = Auth()->setTTL($request->stay ? 10800 : 3600)->attempt($userCred)) {
            return response()->json([
                "type" => "Successful request",
                "message" => "User logged in successfully",
                "token" => $token,
            ], 200);
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
        $credentials = request(["email", "password"]);

        $request['password'] = Hash::make($request->password);

        User::create($request->all());

        $token = Auth()->attempt($credentials);

        return response()->json([
            "type" => "Successful request",
            "message" => "User created successfully",
            "token" => $token
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth()->logout();

        return response()->json([
            "type" => "Successful request",
            "message" => "User logged out successfully"
        ], 200);
    }

    /**
     * Reset the user"s password.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $authUser = Auth()->user();

        $user = User::find($authUser->id);

        if (Hash::check($request->oldPassword, $user->password)) {
            $user->password = Hash::make($request->newPassword);
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
}
