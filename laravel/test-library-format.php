#!/usr/bin/env php
<?php

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n==============================================\n";
echo "Teste Final: Validação de Token Keycloak\n";
echo "==============================================\n\n";

// 1. Obter chave da configuração
$configKey = config('keycloak.realm_public_key');
echo "1. Chave da configuração:\n";
echo "   Tamanho: " . strlen($configKey) . " caracteres\n";
echo "   Primeiros 50 chars: " . substr($configKey, 0, 50) . "...\n\n";

// 2. Aplicar mesma formatação que a biblioteca usa (Token::buildPublicKey)
$formattedKey = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($configKey, 64, "\n", true) . "\n-----END PUBLIC KEY-----";

echo "2. Chave formatada pela biblioteca:\n";
$lines = explode("\n", $formattedKey);
echo "   Primeiras 3 linhas:\n";
foreach (array_slice($lines, 0, 3) as $line) {
    echo "   " . $line . "\n";
}
echo "   ...\n";
echo "   Últimas 2 linhas:\n";
foreach (array_slice($lines, -2) as $line) {
    echo "   " . $line . "\n";
}
echo "\n";

// 3. Testar com OpenSSL (como a biblioteca faz)
echo "3. Teste OpenSSL:\n";
$resource = @openssl_pkey_get_public($formattedKey);

if ($resource) {
    echo "   ✅ SUCESSO! A biblioteca conseguirá validar tokens!\n";
    echo "   A configuração está CORRETA.\n\n";
    openssl_free_key($resource);

    echo "==============================================\n";
    echo "✅ PRONTO PARA TESTAR A API!\n";
    echo "==============================================\n";
    echo "Execute:\n";
    echo "  .\test-api.ps1\n\n";
    exit(0);
} else {
    echo "   ❌ ERRO! OpenSSL falhou\n";
    echo "   Erro: " . openssl_error_string() . "\n\n";

    echo "A chave no .env deve estar SEM delimitadores BEGIN/END.\n";
    echo "A biblioteca adiciona automaticamente.\n\n";
    exit(1);
}

