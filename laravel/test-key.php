#!/usr/bin/env php
<?php

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "==============================================\n";
echo "Teste da Chave Pública do Keycloak\n";
echo "==============================================\n\n";

// Obter a chave
$key = config('keycloak.realm_public_key');

if (!$key) {
    echo "❌ ERRO: Chave não encontrada na configuração\n";
    exit(1);
}

echo "✓ Chave encontrada (" . strlen($key) . " caracteres)\n\n";

// Mostrar preview
echo "Preview da chave:\n";
echo "-------------------\n";
$lines = explode("\n", $key);
foreach (array_slice($lines, 0, 3) as $line) {
    echo $line . "\n";
}
echo "...\n";
foreach (array_slice($lines, -2) as $line) {
    echo $line . "\n";
}
echo "-------------------\n\n";

// Testar com OpenSSL
echo "Testando com OpenSSL...\n";
$resource = @openssl_pkey_get_public($key);

if ($resource) {
    echo "✅ SUCESSO! OpenSSL conseguiu ler a chave!\n";
    echo "A chave está formatada corretamente.\n";
    openssl_free_key($resource);
    exit(0);
} else {
    echo "❌ ERRO! OpenSSL não conseguiu ler a chave\n";
    echo "Erro: " . openssl_error_string() . "\n\n";

    echo "Tentando reformatar...\n";
    // Limpa a chave
    $clean = str_replace(['-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----', "\n", "\r", ' ', '"', "'"], '', $key);

    echo "Chave limpa: " . substr($clean, 0, 50) . "...\n";

    // Reformata
    $formatted = "-----BEGIN PUBLIC KEY-----\n";
    $formatted .= chunk_split($clean, 64, "\n");
    $formatted .= "-----END PUBLIC KEY-----";

    $resource2 = @openssl_pkey_get_public($formatted);
    if ($resource2) {
        echo "✅ Reformatação funcionou!\n\n";
        echo "Atualize o .env com:\n";
        echo "KEYCLOAK_REALM_PUBLIC_KEY=$clean\n";
        openssl_free_key($resource2);
    } else {
        echo "❌ Reformatação também falhou\n";
        echo "Erro: " . openssl_error_string() . "\n";
    }

    exit(1);
}

