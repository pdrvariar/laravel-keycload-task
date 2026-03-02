<?php

return [
    // Keycloak server base URL (for browser redirects)
    'base_url' => env('KEYCLOAK_BASE_URL', 'http://localhost:8080'),

    // Keycloak server internal URL (for backend API calls from container)
    'base_url_internal' => env('KEYCLOAK_BASE_URL_INTERNAL', 'http://keycloak:8080'),

    // Realm name
    'realm' => env('KEYCLOAK_REALM', 'master'),

    // Client ID
    'client_id' => env('KEYCLOAK_CLIENT_ID'),

    // Client Secret
    'client_secret' => env('KEYCLOAK_CLIENT_SECRET'),

    // Redirect URI after login
    'redirect_uri' => env('KEYCLOAK_REDIRECT_URI', 'http://localhost:8000/auth/callback'),

    // Redirect URI after logout
    'logout_redirect_uri' => env('KEYCLOAK_LOGOUT_REDIRECT_URI', 'http://localhost:8000'),

    // ==============================
    // Keycloak Guard Configuration (for API token validation)
    // ==============================

    'realm_public_key' => (function() {
        $key = env('KEYCLOAK_REALM_PUBLIC_KEY');

        if (empty($key)) {
            // Não lança exceção aqui para permitir que outros comandos do artisan funcionem
            // O erro original da biblioteca será lançado se a chave for realmente necessária
            return null;
        }

        // Remove aspas extras que podem vir do .env
        $key = trim($key, '"\'');

        // Remove delimitadores e espaços/quebras de linha se já existirem
        // A biblioteca laravel-keycloak-guard adiciona os delimitadores automaticamente,
        // então devemos retornar APENAS a chave sem delimitadores
        $cleanKey = str_replace(['-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----', "\n", "\r", ' '], '', $key);

        return $cleanKey;
    })(),

    // Token encryption algorithm
    'token_encryption_algorithm' => env('KEYCLOAK_TOKEN_ENCRYPTION_ALGORITHM', 'RS256'),

    // Load user from database when using the guard
    'load_user_from_database' => env('KEYCLOAK_LOAD_USER_FROM_DATABASE', true),

    // Custom method to retrieve user
    'user_provider_custom_retrieve_method' => env('KEYCLOAK_USER_PROVIDER_CUSTOM_RETRIEVE_METHOD', null),

    // User credential attribute
    'user_provider_credential' => env('KEYCLOAK_USER_PROVIDER_CREDENTIAL', 'email'),

    // Token principal attribute
    'token_principal_attribute' => env('KEYCLOAK_TOKEN_PRINCIPAL_ATTRIBUTE', 'email'),

    // Append decoded token to user
    'append_decoded_token' => env('KEYCLOAK_APPEND_DECODED_TOKEN', true),

    // Allowed resources
    'allowed_resources' => env('KEYCLOAK_ALLOWED_RESOURCES', null),

    // Ignore resource validation
    'ignore_resources_validation' => env('KEYCLOAK_IGNORE_RESOURCES_VALIDATION', true),

    // Leeway for token validation (in seconds)
    'leeway' => env('KEYCLOAK_LEEWAY', 60),

    // Input key for token
    'input_key' => env('KEYCLOAK_TOKEN_INPUT_KEY', null)
];
