<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ValidateEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return JsonResponse|RedirectResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse|RedirectResponse
    {
        if ($request->user() && !$request->user()->hasVerifiedEmail()) {
            return response()->json([
                "type" => "Email not verified",
                "message" => "Your email address is not verified"
            ], 422);
        }

        return $next($request);
    }
}
