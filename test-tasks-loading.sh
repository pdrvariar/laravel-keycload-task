#!/bin/bash

# Script para testar as correções de carregamento de tarefas
# Uso: bash test-tasks-loading.sh

echo "🧪 Teste de Carregamento de Tarefas"
echo "==================================="
echo ""

# Cores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Config
API_URL="http://localhost:8000/api"
LOGIN_URL="http://localhost:8000/auth/login"

echo "📝 Step 1: Fazer login e obter token"
echo "------------------------------------"

# Credenciais de teste (ajuste conforme necessário)
EMAIL="teste@example.com"
PASSWORD="password123"

# Login
LOGIN_RESPONSE=$(curl -s -X POST "$LOGIN_URL" \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"$EMAIL\",\"password\":\"$PASSWORD\"}")

TOKEN=$(echo "$LOGIN_RESPONSE" | grep -o '"token":"[^"]*' | cut -d'"' -f4)

if [ -z "$TOKEN" ]; then
  echo -e "${RED}❌ Falha ao obter token${NC}"
  echo "Resposta: $LOGIN_RESPONSE"
  exit 1
fi

echo -e "${GREEN}✅ Token obtido com sucesso${NC}"
echo "Token: ${TOKEN:0:20}..."
echo ""

echo "🔍 Step 2: Testar GET /api/tasks"
echo "--------------------------------"

TASKS_RESPONSE=$(curl -s -X GET "$API_URL/tasks" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

if echo "$TASKS_RESPONSE" | grep -q '"success":true'; then
  echo -e "${GREEN}✅ GET /api/tasks funcionando${NC}"
  echo "Resposta: $(echo $TASKS_RESPONSE | head -c 100)..."
else
  echo -e "${RED}❌ GET /api/tasks falhou${NC}"
  echo "Resposta: $TASKS_RESPONSE"
fi
echo ""

echo "📝 Step 3: Testar POST /api/tasks (criar tarefa)"
echo "----------------------------------------------"

CREATE_RESPONSE=$(curl -s -X POST "$API_URL/tasks" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "description": "Tarefa de teste - '$(date +%s)'",
    "status": "Em Planejamento"
  }')

if echo "$CREATE_RESPONSE" | grep -q '"success":true'; then
  echo -e "${GREEN}✅ POST /api/tasks funcionando${NC}"
  TASK_ID=$(echo "$CREATE_RESPONSE" | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
  echo "Task ID: $TASK_ID"
else
  echo -e "${RED}❌ POST /api/tasks falhou${NC}"
  echo "Resposta: $CREATE_RESPONSE"
fi
echo ""

echo "📋 Step 4: Testar GET /api/tasks/{id} (obter tarefa específica)"
echo "-----------------------------------------------------------"

if [ ! -z "$TASK_ID" ]; then
  GET_TASK_RESPONSE=$(curl -s -X GET "$API_URL/tasks/$TASK_ID" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Accept: application/json")

  if echo "$GET_TASK_RESPONSE" | grep -q '"success":true'; then
    echo -e "${GREEN}✅ GET /api/tasks/{id} funcionando${NC}"
    echo "Resposta: $(echo $GET_TASK_RESPONSE | head -c 100)..."
  else
    echo -e "${RED}❌ GET /api/tasks/{id} falhou${NC}"
    echo "Resposta: $GET_TASK_RESPONSE"
  fi
else
  echo -e "${YELLOW}⚠️  Pulando (nenhuma tarefa foi criada)${NC}"
fi
echo ""

echo "✏️  Step 5: Testar PUT /api/tasks/{id} (atualizar tarefa)"
echo "------------------------------------------------------"

if [ ! -z "$TASK_ID" ]; then
  UPDATE_RESPONSE=$(curl -s -X PUT "$API_URL/tasks/$TASK_ID" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{
      "description": "Tarefa atualizada - '$(date +%s)'",
      "status": "Em Andamento"
    }')

  if echo "$UPDATE_RESPONSE" | grep -q '"success":true'; then
    echo -e "${GREEN}✅ PUT /api/tasks/{id} funcionando${NC}"
    echo "Resposta: $(echo $UPDATE_RESPONSE | head -c 100)..."
  else
    echo -e "${RED}❌ PUT /api/tasks/{id} falhou${NC}"
    echo "Resposta: $UPDATE_RESPONSE"
  fi
else
  echo -e "${YELLOW}⚠️  Pulando (nenhuma tarefa foi criada)${NC}"
fi
echo ""

echo "🗑️  Step 6: Testar DELETE /api/tasks/{id} (deletar tarefa)"
echo "------------------------------------------------------"

if [ ! -z "$TASK_ID" ]; then
  DELETE_RESPONSE=$(curl -s -X DELETE "$API_URL/tasks/$TASK_ID" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Accept: application/json")

  if echo "$DELETE_RESPONSE" | grep -q '"success":true'; then
    echo -e "${GREEN}✅ DELETE /api/tasks/{id} funcionando${NC}"
    echo "Resposta: $(echo $DELETE_RESPONSE | head -c 100)..."
  else
    echo -e "${RED}❌ DELETE /api/tasks/{id} falhou${NC}"
    echo "Resposta: $DELETE_RESPONSE"
  fi
else
  echo -e "${YELLOW}⚠️  Pulando (nenhuma tarefa foi criada)${NC}"
fi
echo ""

echo "==================================="
echo "✅ Testes completados!"
echo ""
echo "Se todos os testes passaram:"
echo "- Dashboard deve carregar tarefas corretamente"
echo "- Criar, editar e deletar tarefas devem funcionar"
echo "- Filtros e busca devem trabalhar"
echo ""


