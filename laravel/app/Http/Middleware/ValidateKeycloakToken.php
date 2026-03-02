<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ValidateKeycloakToken
{
    /**
     * Handle an incoming request.
     * Este middleware apenas valida a presença e estrutura básica do token.
     * O guard keycloak fará a validação criptográfica completa.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se há um token Bearer
        if (!$request->bearerToken()) {
            Log::warning('API request without bearer token from ' . $request->ip());
            return response()->json([
                'success' => false,
                'message' => 'Token de autenticação não fornecido'
            ], 401);
        }

        // Se o token foi fornecido, deixar o guard keycloak fazer a validação completa
        return $next($request);
    }
}

