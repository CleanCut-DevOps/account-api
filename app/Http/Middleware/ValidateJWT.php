<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ValidateJWT
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse|Response) $next
     * @return JsonResponse|RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next): JsonResponse|RedirectResponse|Response
    {
        try {
            JWTAuth::parseToken()->authenticate();

            return $next($request);
        } catch (Exception $e) {
            return response()->json([
                "type" => "Unauthenticated",
                "message" => "Invalid credentials"
            ], 401);
        }
    }
}
