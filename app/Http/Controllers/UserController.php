<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ValidateJWT;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     * Set middleware for all functions
     *
     * @return void
     * @throws ValidationException
     */
    public function __construct()
    {
        $this->middleware(ValidateJWT::class)->except(['login', 'register']);

        $this->validate('login', [
            'email' => ['required', 'email', 'max:255'],
            'remember' => ['required', 'boolean'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'email.required' => 'We need to know your email address',
            'email.email' => 'Your email address is invalid',
            'email.max' => 'Your email address is too long',
            'remember.required' => 'Remember me field is required',
            'remember.boolean' => 'Remember me value is invalid',
            'password.required' => 'We need to know your password',
            'password.string' => 'Your password is invalid',
            'password.min' => 'Your password is too short',
        ]);

        $this->validate('register', [
            "name" => ["required", "string", "max:255"],
            "phone" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255", "unique:user"],
            "password" => ["required", Password::min(8)->mixedCase()->numbers()->uncompromised()],
        ], [
            "name.required" => "We need to who you are",
            "name.string" => "Your name is invalid",
            "name.max" => "Your name is too long",
            "email.required" => "We need to know your email address",
            "email.string" => "Your email address is invalid",
            "email.email" => "Your email address is invalid",
            "email.max" => "Your email address is too long",
            "email.unique" => "Your email address is already in use",
            "phone.required" => "We need to know how to contact you",
            "phone.string" => "Your phone number is invalid",
            "phone.max" => "Your phone number is too long",
            "password.required" => "We need to know your password"
        ]);

        $this->validate('reset', [
            "newPassword" => ["required", "string", Password::min(8)->mixedCase()->numbers()->uncompromised()],
            "oldPassword" => ["required", "string", Password::default()],
        ], [
            "newPassword.required" => "Please indicate your new password",
            "newPassword.string" => "Your new password is invalid",
            "oldPassword.required" => "Please indicate your old password",
            "oldPassword.string" => "Your old password is invalid",
        ]);
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
        $emailCred = $request->only('email', 'password');

        if ($token = Auth::setTTL(request('remember') ? 60 * 24 * 7 : 60 * 24)->attempt($emailCred)) {
            return response()->json([
                'type' => 'Successful request',
                'message' => 'Logged in successfully',
                'token' => $token
            ]);
        } else {
            return response()->json([
                'type' => 'Unauthorized',
                'message' => 'Invalid credentials'
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
        $request['password'] = Hash::make(request('password'));

        $user = User::create($request->only([
            'name',
            'email',
            'phone',
            'password'
        ]));

        event(new Registered($user));

        return response()->json([
            'type' => 'Successful request',
            'message' => 'User created successfully'
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
            'type' => 'Successful request',
            'message' => 'User logged out successfully'
        ], 200);
    }

    /**
     * Reset the user's password.
     *
     * @return JsonResponse
     */
    public function reset(): JsonResponse
    {
        $userID = Auth::payload()->get('sub');

        $user = User::find($userID);

        if (Hash::check(request('oldPassword'), $user->password)) {
            $user->password = Hash::make(request('newPassword'));
            $user->save();

            return response()->json([
                'type' => 'Successful request',
                'message' => 'Password changed successfully'
            ], 200);
        } else {
            return response()->json([
                'type' => 'Invalid data',
                'message' => 'Request data is invalid'
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
            'type' => 'Successful request',
            'message' => 'User data retrieved successfully',
            'user' => $user
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
            'type' => 'Successful request',
            'message' => 'User data updated successfully',
            'user' => $user
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
            'type' => 'Successful request',
            'message' => 'User data deleted successfully'
        ]);
    }
}
