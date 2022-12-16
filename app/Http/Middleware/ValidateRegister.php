<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JSONResponse;
use Illuminate\Validation\Rules\Password;

class ValidateRegister
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (JSONResponse|RedirectResponse) $next
     * @return JSONResponse|RedirectResponse
     */
    public function handle(Request $request, Closure $next): JSONResponse|RedirectResponse
    {
        try {
            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'contact' => ['required', 'string', 'max:255'],
                'password' => ['required', Password::min(8)->mixedCase()->numbers()->uncompromised()],
                'avatar' => ['nullable', 'string'],
            ]);

            return $next($request);
        } catch (\Exception $e) {
            $errors = collect($e->errors());

            return response()->json([
                "type" => "Invalid data",
                "message" => "The data provided in the request is invalid",
                "errors" => $errors->map(function ($error) {
                    return $error[0];
                }),
            ], 422);
        }
    }
}
