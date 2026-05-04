<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsDeveloper
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->isDeveloper()) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
