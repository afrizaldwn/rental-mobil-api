<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseFormatter;
use Closure;
use Illuminate\Http\Request;

class BasicAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = (string) $request->getUser();
        $pass = (string) $request->getPassword();

        $validUser = (string) env('BASIC_AUTH_USER');
        $validPass = (string) env('BASIC_AUTH_PASS');

        if (!hash_equals($validUser, $user) || !hash_equals($validPass, $pass)) {
            return ResponseFormatter::make($request, [
                'status'  => 'error',
                'message' => 'Unauthorized. Basic Auth diperlukan.',
            ], 401)->header('WWW-Authenticate', 'Basic realm="Rental Mobil API"');
        }

        return $next($request);
    }
}