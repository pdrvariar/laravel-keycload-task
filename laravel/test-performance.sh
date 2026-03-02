#!/bin/bash

# Script de Teste de Performance
# Execute dentro do container: docker-compose exec app bash test-performance.sh

echo "================================================"
echo "TESTE DE PERFORMANCE - Laravel com Keycloak"
echo "================================================"
echo ""

# Test 1: Homepage
echo "📊 Teste 1: Carregando homepage (/)..."
time curl -s http://localhost:80 > /dev/null
echo ""

# Test 2: Login redirect
echo "📊 Teste 2: Redirecionamento para login..."
time curl -s http://localhost:80/login > /dev/null
echo ""

# Test 3: Multiple requests
echo "📊 Teste 3: 10 requisições consecutivas..."
start_time=$(date +%s.%N)
for i in {1..10}; do
    curl -s http://localhost:80 > /dev/null
done
end_time=$(date +%s.%N)
elapsed=$(echo "$end_time - $start_time" | bc)
avg=$(echo "scale=3; $elapsed / 10" | bc)
echo "Total: ${elapsed}s"
echo "Média por requisição: ${avg}s"
echo ""

# Test 4: Check PHP-FPM status
echo "📊 Teste 4: Status do PHP-FPM..."
php -v | head -1
php -i | grep opcache.enable
echo ""

echo "================================================"
echo "✅ TESTES CONCLUÍDOS!"
echo "================================================"
echo ""
echo "Se o tempo médio está abaixo de 1 segundo, a otimização funcionou! 🚀"

