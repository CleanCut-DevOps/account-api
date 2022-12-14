<?php

namespace App\Http\Controllers;

use App\Models\user;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function update(Request $request): JSONResponse
    {
        $validationRules = [
            'avatar' => ['nullable', 'string'],
            'username' => ['nullable', 'string', 'max:48'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'contact' => ['nullable', 'string'],
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

        if (!empty($request->username) && $user->username != $request->username) $user->username = $request->username;
        if (!empty($request->contact) && $user->contact != $request->contact) $user->contact = $request->contact;
        if (!empty($request->avatar) && $user->avatar != $request->avatar) $user->avatar = $request->avatar;
        if (!empty($request->email) && $user->email != $request->email) $user->email = $request->email;

        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'account' => $user
        ], 201);
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

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
