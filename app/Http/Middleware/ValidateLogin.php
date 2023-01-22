<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JSONResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidateLogin
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
                "email" => ["required", "email", "max:255"],
                "stay" => ["required", "boolean"],
                "password" => ["required", "string", "min:8"],
            ], [
                "email.required" => "Email is required",
                "email.email" => "Email is invalid",
                "email.max" => "Email is too long",
                "stay.required" => "Remember me is required",
                "stay.boolean" => "Remember me is invalid",
                "password.required" => "Password is required",
                "password.string" => "Password is invalid",
                "password.min" => "Password is too short",
            ]);

            return $next($request);
        } catch (ValidationException $e) {
            $errors = collect($e->errors());

            return response()->json([
                "type" => "Invalid data",
                "message" => $e->getMessage(),
                "errors" => $errors->map(fn($error) => $error[0]),
            ], 400);
        }
    }
}
