<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JSONResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
                'stay' => ['required', 'boolean'],
                'email' => [Rule::requiredIf(!$request->username), 'email', 'max:255'],
                'username' => [Rule::requiredIf(!$request->email)],
                'password' => ['required', 'string', 'min:8'],
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
