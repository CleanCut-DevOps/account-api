<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JSONResponse;
use Illuminate\Validation\Rule;

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
