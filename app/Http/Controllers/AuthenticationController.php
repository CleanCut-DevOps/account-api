<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthenticationController extends Controller
{

    /**
     * Create a new AuthController instance.
     * Set middleware for logout & account functions
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
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
        $validationRules = [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact' => ['required', 'string', 'max:255'],
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->uncompromised()],
            'avatar' => ['nullable', 'string'],
        ];

        $validator = Validator::make(request()->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                "type" => "Invalid data",
                "message" => "The data provided in the request is invalid",
                "errorFields" => $validator->errors()->messages()
            ], 422);
        }

        $credentials = request(['email', 'password']);

        $user = User::create([
            'email' => request('email'),
            'avatar' => request('avatar'),
            'contact' => request('contact'),
            'username' => request('username'),
            'password' => Hash::make($request->password),
        ]);

        $token = Auth()->attempt($credentials);

        return response()->json([
            'message' => 'User created successfully',
            'token' => $token,
            'account' => $user
        ], 201);
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
        $userCred = $request->only('username', 'password');

        if ($token = Auth()->setTTL($request->stay ? 10800 : 3600)->attempt($emailCred)) {

            $user = Auth()->user();

            return response()->json([
                'message' => 'User logged in successfully by email',
                'token' => $token,
                'account' => $user,
            ], 200);
        } else if ($token = Auth()->setTTL($request->stay ? 10800 : 3600)->attempt($userCred)) {

            $user = Auth()->user();

            return response()->json([
                'message' => 'User logged in successfully by username',
                'token' => $token,
                'account' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
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
        $validationRules = [
            'newPassword' => ['required', Password::min(8)->mixedCase()->numbers()->uncompromised()],
            'oldPassword' => ['required', Password::default()],
        ];

        $validator = Validator::make(request()->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                "type" => "Invalid data",
                "message" => "The data provided in the request is invalid",
                "errorFields" => $validator->errors()->messages()
            ], 422);
        }

        $authUser = Auth()->user();

        $user = User::find($authUser->id);

        if (Hash::check($request->oldPassword, $user->password)) {
            $user->password = Hash::make($request->newPassword);
            $user->save();

            return response()->json([
                'message' => 'Password changed successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
    }
}
