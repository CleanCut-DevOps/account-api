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
                'full_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'phone_number' => ['required', 'string',  'max:255']
            ]);

            return $next($request);
        } catch (ValidationException $e) {
            $errors = collect($e->errors());

            return response()->json([
                "type" => "Invalid data",
                "message" => $e->getMessage(),
                "errors" => $errors->map(fn ($error) => $error[0]),
            ], 400);
        }
    }
}
