#!/bin/bash

# Script para configurar a realm do Keycloak
# Aumenta o tempo de vida dos tokens para 2 horas

# Aguardar o Keycloak ficar pronto
sleep 10

# Variáveis
KEYCLOAK_URL="http://localhost:8080"
REALM="task-controller"
ADMIN_USER="admin"
ADMIN_PASSWORD="admin"
TOKEN_LIFETIME_SECONDS=7200  # 2 horas

# Obter token de acesso do admin
echo "Obtendo token de admin..."
ADMIN_TOKEN=$(curl -s -X POST \
  "$KEYCLOAK_URL/realms/master/protocol/openid-connect/token" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "username=$ADMIN_USER" \
  -d "password=$ADMIN_PASSWORD" \
  -d "client_id=admin-cli" \
  -d "grant_type=password" | jq -r '.access_token')

if [ -z "$ADMIN_TOKEN" ] || [ "$ADMIN_TOKEN" == "null" ]; then
  echo "❌ Erro ao obter token de admin"
  exit 1
fi

echo "✓ Token de admin obtido"

# Obter configuração atual da realm
echo "Obtendo configuração da realm..."
curl -s -X GET \
  "$KEYCLOAK_URL/admin/realms/$REALM" \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" | jq '.' > /tmp/realm-config.json

# Atualizar tempo de vida dos tokens
echo "Atualizando tempo de vida dos tokens para 2 horas..."
jq '.accessTokenLifespan = '"$TOKEN_LIFETIME_SECONDS"' |
    .refreshTokenLifespan = 2592000 |
    .offlineSessionIdleTimeout = 2592000' /tmp/realm-config.json > /tmp/realm-config-updated.json

# Atualizar realm
curl -s -X PUT \
  "$KEYCLOAK_URL/admin/realms/$REALM" \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d @/tmp/realm-config-updated.json

echo "✓ Configuração de tokens atualizada!"

# Configurar client específico também
echo "Configurando client task-app..."
CLIENT_ID=$(curl -s -X GET \
  "$KEYCLOAK_URL/admin/realms/$REALM/clients" \
  -H "Authorization: Bearer $ADMIN_TOKEN" | jq -r '.[] | select(.clientId=="task-app") | .id')

if [ -n "$CLIENT_ID" ]; then
  curl -s -X GET \
    "$KEYCLOAK_URL/admin/realms/$REALM/clients/$CLIENT_ID" \
    -H "Authorization: Bearer $ADMIN_TOKEN" | jq '.' > /tmp/client-config.json

  jq '.accessTokenLifespan = '"$TOKEN_LIFETIME_SECONDS" /tmp/client-config.json > /tmp/client-config-updated.json

  curl -s -X PUT \
    "$KEYCLOAK_URL/admin/realms/$REALM/clients/$CLIENT_ID" \
    -H "Authorization: Bearer $ADMIN_TOKEN" \
    -H "Content-Type: application/json" \
    -d @/tmp/client-config-updated.json

  echo "✓ Client task-app configurado!"
else
  echo "⚠ Client task-app não encontrado"
fi

echo "✓ Configuração do Keycloak concluída!"

