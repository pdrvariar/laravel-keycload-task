#!/bin/bash

echo "================================================"
echo "Verificação da Correção do Keycloak Public Key"
echo "================================================"
echo ""

# Verifica se o arquivo .env existe
if [ ! -f laravel/.env ]; then
    echo "❌ ERRO: Arquivo laravel/.env não encontrado!"
    exit 1
fi

# Verifica se a chave está no .env
if grep -q "KEYCLOAK_REALM_PUBLIC_KEY" laravel/.env; then
    echo "✅ KEYCLOAK_REALM_PUBLIC_KEY encontrada no .env"

    # Mostra os primeiros 50 caracteres da chave
    KEY=$(grep "KEYCLOAK_REALM_PUBLIC_KEY" laravel/.env | cut -d'=' -f2 | tr -d '"' | cut -c1-50)
    echo "   Primeiros 50 caracteres: $KEY..."
else
    echo "❌ ERRO: KEYCLOAK_REALM_PUBLIC_KEY não encontrada no .env"
    echo ""
    echo "Execute este comando para adicionar a chave:"
    echo "================================================"
    echo "Acesse: http://localhost:8000/setup/keycloak-key"
    echo "Ou execute:"
    echo "curl -s http://localhost:8080/realms/task-controller | jq -r '.public_key'"
    echo "================================================"
    exit 1
fi

echo ""
echo "Verificando containers..."

# Verifica se os containers estão rodando
if ! docker compose ps | grep -q "task_app"; then
    echo "⚠️  AVISO: Container task_app pode não estar rodando"
    echo "   Execute: docker compose up -d"
else
    echo "✅ Container task_app está rodando"
fi

echo ""
echo "Testando configuração dentro do container..."

# Testa se a chave está disponível dentro do container
docker compose exec -T app php -r "
    echo (getenv('KEYCLOAK_REALM_PUBLIC_KEY') ? '✅ Chave disponível no container' : '❌ Chave NÃO disponível no container') . PHP_EOL;
" 2>/dev/null || echo "⚠️  Não foi possível verificar dentro do container (ele pode estar iniciando)"

echo ""
echo "Limpando cache do Laravel..."
docker compose exec -T app php artisan config:clear 2>/dev/null && echo "✅ Cache limpo" || echo "⚠️  Não foi possível limpar cache"
docker compose exec -T app php artisan config:cache 2>/dev/null && echo "✅ Cache recriado" || echo "⚠️  Não foi possível recriar cache"

echo ""
echo "================================================"
echo "Próximos Passos:"
echo "================================================"
echo "1. Faça login em: http://localhost:8000/login"
echo "   - Usuário: admin / admin123"
echo ""
echo "2. Obtenha um token via API:"
echo "   curl -X POST http://localhost:8080/realms/task-controller/protocol/openid-connect/token \\"
echo "     -d 'client_id=task-app' \\"
echo "     -d 'client_secret=XRugFZF5nv06ME45GvakdCs4l7Yrh7V5' \\"
echo "     -d 'grant_type=password' \\"
echo "     -d 'username=admin' \\"
echo "     -d 'password=admin123'"
echo ""
echo "3. Use o token para criar uma tarefa:"
echo "   curl -X POST http://localhost:8000/api/tasks \\"
echo "     -H 'Authorization: Bearer {SEU_TOKEN}' \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"title\":\"Teste\",\"description\":\"Teste\",\"status\":\"pending\"}'"
echo ""
echo "================================================"

