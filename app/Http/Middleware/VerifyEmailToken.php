<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class VerifyEmailToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return JsonResponse|RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next): JsonResponse|RedirectResponse|Response
    {
        try {
            Auth::login(User::whereId(request('id'))->firstOrFail());

            return $next($request);
        } catch (Exception $e) {
            $url = config('app.url');

            return redirect($url);
        }
    }
}
