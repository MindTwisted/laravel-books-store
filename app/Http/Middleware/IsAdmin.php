<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ('admin' !== $request->user()->role)
        {
            return response()->json([
                'message' => "Forbidden."
            ], 403);
        }

        return $next($request);
    }
}
