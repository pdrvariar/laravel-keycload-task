#!/usr/bin/env php
<?php

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

echo "\n==============================================\n";
echo "Teste de Criação de Tarefa via API\n";
echo "==============================================\n\n";

// Passo 1: Obter token do Keycloak
echo "1. Obtendo token do Keycloak...\n";

$ch = curl_init('http://keycloak:8080/realms/task-controller/protocol/openid-connect/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'client_id' => 'task-app',
    'client_secret' => 'XRugFZF5nv06ME45GvakdCs4l7Yrh7V5',
    'grant_type' => 'password',
    'username' => 'admin',
    'password' => 'admin123',
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "   ✗ Erro ao obter token (HTTP $httpCode)\n";
    echo "   Resposta: $response\n";
    exit(1);
}

$tokenData = json_decode($response, true);
$token = $tokenData['access_token'] ?? null;

if (!$token) {
    echo "   ✗ Token não encontrado na resposta\n";
    exit(1);
}

echo "   ✓ Token obtido (" . strlen($token) . " caracteres)\n";
echo "   Primeiros 50 chars: " . substr($token, 0, 50) . "...\n\n";

// Passo 2: Criar tarefa via API local
echo "2. Criando tarefa via API...\n";

$taskData = json_encode([
    'description' => 'Tarefa de teste via PHP - ' . date('H:i:s'),
    'status' => 'Em Planejamento',
]);

$ch = curl_init('http://localhost/api/tasks');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $taskData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $token,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   HTTP Status: $httpCode\n";
echo "   Resposta:\n";
echo "   " . str_replace("\n", "\n   ", json_encode(json_decode($response), JSON_PRETTY_PRINT)) . "\n\n";

if ($httpCode === 201) {
    echo "✅ SUCESSO! Tarefa criada com sucesso!\n";
    exit(0);
} else {
    echo "❌ ERRO! Falha ao criar tarefa.\n";
    exit(1);
}

