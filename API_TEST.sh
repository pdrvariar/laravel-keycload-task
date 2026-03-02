#!/bin/bash

# =====================================================
# TESTE DE API - CRUD de Tarefas
# =====================================================
# Script para testar todos os endpoints da API

# Configurações
API_URL="http://localhost/api"
BEARER_TOKEN="seu_token_aqui"  # Substitua com um token válido do Keycloak
USER_ID=1

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║     TESTE DE API - CRUD DE TAREFAS                       ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════════════════╝${NC}"
echo ""

# =====================================================
# 1. LISTAR TODAS AS TAREFAS
# =====================================================
echo -e "${YELLOW}[1/6]${NC} Listando todas as tarefas..."
echo ""

curl -X GET "${API_URL}/tasks" \
  -H "Authorization: Bearer ${BEARER_TOKEN}" \
  -H "Content-Type: application/json" \
  -w "\n\nStatus: %{http_code}\n\n"

read -p "Pressione ENTER para continuar..."
echo ""

# =====================================================
# 2. CRIAR NOVA TAREFA
# =====================================================
echo -e "${YELLOW}[2/6]${NC} Criando nova tarefa..."
echo ""

curl -X POST "${API_URL}/tasks" \
  -H "Authorization: Bearer ${BEARER_TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "description": "Implementar novo CRUD de tarefas",
    "status": "Em Andamento"
  }' \
  -w "\n\nStatus: %{http_code}\n\n"

# Salve o ID da tarefa criada
TASK_ID=2

read -p "Pressione ENTER para continuar..."
echo ""

# =====================================================
# 3. VISUALIZAR TAREFA ESPECÍFICA
# =====================================================
echo -e "${YELLOW}[3/6]${NC} Visualizando tarefa ID ${TASK_ID}..."
echo ""

curl -X GET "${API_URL}/tasks/${TASK_ID}" \
  -H "Authorization: Bearer ${BEARER_TOKEN}" \
  -H "Content-Type: application/json" \
  -w "\n\nStatus: %{http_code}\n\n"

read -p "Pressione ENTER para continuar..."
echo ""

# =====================================================
# 4. ATUALIZAR TAREFA
# =====================================================
echo -e "${YELLOW}[4/6]${NC} Atualizando tarefa ID ${TASK_ID}..."
echo ""

curl -X PUT "${API_URL}/tasks/${TASK_ID}" \
  -H "Authorization: Bearer ${BEARER_TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "description": "Implementar novo CRUD de tarefas - ATUALIZADO",
    "status": "Concluído"
  }' \
  -w "\n\nStatus: %{http_code}\n\n"

read -p "Pressione ENTER para continuar..."
echo ""

# =====================================================
# 5. LISTAR USUÁRIOS (ADMIN ONLY)
# =====================================================
echo -e "${YELLOW}[5/6]${NC} Listando usuários..."
echo ""

curl -X GET "${API_URL}/users" \
  -H "Authorization: Bearer ${BEARER_TOKEN}" \
  -H "Content-Type: application/json" \
  -w "\n\nStatus: %{http_code}\n\n"

read -p "Pressione ENTER para continuar..."
echo ""

# =====================================================
# 6. DELETAR TAREFA
# =====================================================
echo -e "${YELLOW}[6/6]${NC} Deletando tarefa ID ${TASK_ID}..."
echo ""

curl -X DELETE "${API_URL}/tasks/${TASK_ID}" \
  -H "Authorization: Bearer ${BEARER_TOKEN}" \
  -H "Content-Type: application/json" \
  -w "\n\nStatus: %{http_code}\n\n"

echo ""
echo -e "${GREEN}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║     TESTE FINALIZADO!                                    ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════════════════╝${NC}"

# =====================================================
# TESTES ADICIONAIS (DESCOMENTE PARA USAR)
# =====================================================

# Filtrar por status
# curl -X GET "${API_URL}/tasks?status=Em%20Andamento" \
#   -H "Authorization: Bearer ${BEARER_TOKEN}"

# Filtrar por usuário (admin only)
# curl -X GET "${API_URL}/tasks?user_id=1" \
#   -H "Authorization: Bearer ${BEARER_TOKEN}"

# Ordenar por data (asc)
# curl -X GET "${API_URL}/tasks?sort_by=created_at&sort_order=asc" \
#   -H "Authorization: Bearer ${BEARER_TOKEN}"

# Ordenar por descrição
# curl -X GET "${API_URL}/tasks?sort_by=description&sort_order=asc" \
#   -H "Authorization: Bearer ${BEARER_TOKEN}"

