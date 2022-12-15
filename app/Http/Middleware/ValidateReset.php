<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ValidateReset
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
                'newPassword' => ['required', Password::min(8)->mixedCase()->numbers()->uncompromised()],
                'oldPassword' => ['required', Password::default()],
            ]);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                "type" => "Invalid data",
                "message" => "The data provided in the request is invalid",
                "errorFields" => $e->errors(),
            ], 422);
        }
    }
}
