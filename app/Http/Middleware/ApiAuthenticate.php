<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthenticate
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::guard($guards[0] ?? 'sanctum')->guest()) {
            return response()->json([
                'message' => 'لطفا لاگین کنید'
            ], 401);
        }

        return $next($request);
    }
}
