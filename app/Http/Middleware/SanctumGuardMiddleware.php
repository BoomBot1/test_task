<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SanctumGuardMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        auth()->setDefaultDriver('sanctum');

        return $next($request);
    }
}
