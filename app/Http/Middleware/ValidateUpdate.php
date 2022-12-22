<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
                'username' => ['nullable', 'string', 'max:255', 'unique:user'],
                'email' => ['nullable', 'string', 'email', 'max:255', 'unique:user'],
                'contact' => ['nullable', 'string'],
            ]);

            return $next($request);
        } catch (ValidationException $e) {
            $errors = array_merge(...array_values($e->errors()));

            return response()->json([
                "type" => "Invalid data",
                "message" => $e->getMessage(),
                "errors" => $errors,
            ], 422);
        }
    }
}
