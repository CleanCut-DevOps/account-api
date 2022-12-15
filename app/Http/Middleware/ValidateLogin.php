<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JSONResponse;

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
                'email' => ['required_unless:username,null', 'email', 'max:255'],
                'username' => ['required_unless:email,null'],
                'password' => ['required', 'string', 'min:8'],
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
