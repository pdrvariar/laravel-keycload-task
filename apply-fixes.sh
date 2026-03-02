#!/bin/bash
# Script rápido para aplicar todas as correções

echo "🚀 Aplicando todas as correções..."
echo ""

# 1. Parar containers
echo "1️⃣  Parando containers..."
docker-compose down -v

echo ""
echo "2️⃣  Aguardando 5 segundos..."
sleep 5

# 3. Iniciar containers
echo "3️⃣  Iniciando containers com novas configurações..."
docker-compose up -d

echo ""
echo "4️⃣  Aguardando Keycloak inicializar (2-3 minutos)..."
echo "   Você pode monitorar com: docker-compose logs -f keycloak"
sleep 30

echo ""
echo "✅ Todos os containers iniciados!"
echo ""
echo "📋 Próximos passos:"
echo "   1. Aguardar 2-3 minutos para Keycloak ficar pronto"
echo "   2. Abrir: http://localhost:8000"
echo "   3. Fazer login"
echo "   4. Testar criar tarefa"
echo "   5. Verificar se token dura 2 horas"
echo ""
echo "📖 Para mais detalhes, veja: CHECKLIST_IMPLEMENTACAO.md"
echo ""

