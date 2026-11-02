<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user terautentikasi dan memiliki role customer (atau user)
        if (!$request->user() || !in_array($request->user()->role, ['customer', 'user'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Customer access only.'
            ], 403);
        }

        return $next($request);
    }
}

