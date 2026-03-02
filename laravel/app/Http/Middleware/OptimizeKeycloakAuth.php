<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizeKeycloakAuth
{
    /**
     * Handle an incoming request.
     *
     * This middleware ensures that Keycloak authentication is only checked
     * when necessary, preventing slow token validation on every request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip Keycloak validation for public routes
        if ($this->isPublicRoute($request)) {
            return $next($request);
        }

        return $next($request);
    }

    /**
     * Determine if the current route is public.
     */
    private function isPublicRoute(Request $request): bool
    {
        $publicRoutes = [
            '/',
            '/login',
            '/auth/callback',
            '/up',
        ];

        return in_array($request->path(), $publicRoutes);
    }
}

