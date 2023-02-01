<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function validate(string $route, array $rules, array $messages = [])
    {
        $this->middleware(function (Request $request, Closure $next) use ($rules, $messages) {
            $validator = Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                $errors = collect($validator->errors());

                return response([
                    "type" => "Invalid data",
                    "message" => $validator->errors(),
                    "fields" => $errors->map(fn($error) => $error[0])
                ], 400);
            } else {
                return $next($request);
            }
        })
            ->only($route);
    }
}
