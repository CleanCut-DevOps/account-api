<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ValidateUpdate
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
                'avatar' => ['nullable', 'string'],
                'username' => ['nullable', 'string', 'max:48', 'unique:users'],
                'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
                'contact' => ['nullable', 'string'],
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
