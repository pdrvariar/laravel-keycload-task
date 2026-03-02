<?php

echo "==============================================\n";
echo "Diagnóstico da Chave Pública do Keycloak\n";
echo "==============================================\n\n";

// 1. Verificar variável de ambiente
$envKey = getenv('KEYCLOAK_REALM_PUBLIC_KEY');
echo "1. Chave do .env:\n";
if ($envKey) {
    echo "   ✓ Encontrada (" . strlen($envKey) . " caracteres)\n";
    echo "   Primeiros 50 chars: " . substr($envKey, 0, 50) . "...\n";
    echo "   Últimos 50 chars: ..." . substr($envKey, -50) . "\n";
} else {
    echo "   ✗ NÃO encontrada\n";
}

echo "\n";

// 2. Verificar configuração Laravel
echo "2. Chave da configuração Laravel:\n";
$configKey = config('keycloak.realm_public_key');
if ($configKey) {
    echo "   ✓ Encontrada (" . strlen($configKey) . " caracteres)\n";
    echo "   Primeiros 100 chars:\n";
    echo "   " . substr($configKey, 0, 100) . "\n";
} else {
    echo "   ✗ NÃO encontrada\n";
}

echo "\n";

// 3. Verificar formato da chave
echo "3. Análise do formato:\n";
if ($configKey) {
    $hasBegin = strpos($configKey, '-----BEGIN PUBLIC KEY-----') !== false;
    $hasEnd = strpos($configKey, '-----END PUBLIC KEY-----') !== false;
    $hasNewlines = strpos($configKey, "\n") !== false;

    echo "   Tem BEGIN: " . ($hasBegin ? "✓ Sim" : "✗ Não") . "\n";
    echo "   Tem END: " . ($hasEnd ? "✓ Sim" : "✗ Não") . "\n";
    echo "   Tem quebras de linha: " . ($hasNewlines ? "✓ Sim" : "✗ Não") . "\n";

    if (!$hasBegin || !$hasEnd) {
        echo "\n   ⚠️ PROBLEMA: Faltam delimitadores!\n";
    }
}

echo "\n";

// 4. Testar se OpenSSL consegue ler a chave
echo "4. Teste OpenSSL:\n";
if ($configKey) {
    $resource = @openssl_pkey_get_public($configKey);
    if ($resource) {
        echo "   ✓ OpenSSL conseguiu ler a chave!\n";
        openssl_free_key($resource);
    } else {
        echo "   ✗ OpenSSL NÃO conseguiu ler a chave\n";
        echo "   Erro: " . openssl_error_string() . "\n";

        // Tentar formatar corretamente
        echo "\n5. Tentando reformatar a chave:\n";

        // Remove delimitadores e espaços
        $cleanKey = str_replace(['-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----', "\n", "\r", ' '], '', $configKey);

        // Remove aspas se houver
        $cleanKey = trim($cleanKey, '"');

        echo "   Chave limpa (" . strlen($cleanKey) . " chars): " . substr($cleanKey, 0, 50) . "...\n";

        // Formata corretamente
        $formattedKey = "-----BEGIN PUBLIC KEY-----\n";
        $formattedKey .= chunk_split($cleanKey, 64, "\n");
        $formattedKey .= "-----END PUBLIC KEY-----\n";

        echo "\n   Chave formatada:\n";
        echo "   " . str_replace("\n", "\n   ", trim($formattedKey)) . "\n";

        // Testa novamente
        $resource = @openssl_pkey_get_public($formattedKey);
        if ($resource) {
            echo "\n   ✓ Chave reformatada funciona!\n";
            openssl_free_key($resource);

            echo "\n==============================================\n";
            echo "SOLUÇÃO: Substitua no .env por:\n";
            echo "==============================================\n";
            echo "KEYCLOAK_REALM_PUBLIC_KEY=\"$cleanKey\"\n";
            echo "==============================================\n";
        } else {
            echo "\n   ✗ Ainda não funciona: " . openssl_error_string() . "\n";
        }
    }
}

echo "\n";

