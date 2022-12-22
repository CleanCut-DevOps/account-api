<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JSONResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

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
                'username' => ['required', 'string', 'max:255', 'unique:user'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:user'],
                'contact' => ['required', 'string', 'max:255'],
                'password' => ['required', Password::min(8)->mixedCase()->numbers()->uncompromised()],
                'avatar' => ['nullable', 'string'],
            ]);

            return $next($request);
        } catch (ValidationException $e) {
            $errors = array_merge(...array_values($e->errors()));

            return response()->json([
                "type" => "Invalid data",
                "message" => $e->getMessage(),
                "errors" => $errors,
            ], 400);
        }
    }
}
