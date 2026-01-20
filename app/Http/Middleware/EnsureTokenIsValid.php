<?php

namespace App\Http\Middleware;

use App\Services\JWTService;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $service = app(JWTService::class);
        abort_unless($service->isValid($request->bearerToken()), 403, 'Invalid token.');

        return $next($request);
    }
}
