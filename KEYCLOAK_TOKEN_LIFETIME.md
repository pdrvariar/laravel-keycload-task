# Configuração de Token do Keycloak - 2 Horas

Este documento explica como aumentar o tempo de vida dos tokens do Keycloak para 2 horas.

## Métodos de Configuração

### Método 1: Importação de Realm (RECOMENDADO - Automático)

O arquivo `keycloak/import/realm-export.json` contém a configuração completa da realm com:
- **accessTokenLifespan**: 7200 segundos (2 horas)
- **refreshTokenLifespan**: 2592000 segundos (30 dias)
- **offlineSessionIdleTimeout**: 2592000 segundos (30 dias)

Quando você inicia o Keycloak com `docker-compose up`, ele importa automaticamente este arquivo.

**Como usar:**
```bash
# Reiniciar containers do zero
docker-compose down -v
docker-compose up
```

### Método 2: Comando Artisan (Manual)

Se o arquivo de importação não funcionou ou você precisa reconfigura depois:

```bash
# Dentro do container Laravel ou via artisan
php artisan keycloak:configure-token-lifetime
```

Este comando:
1. Conecta ao Keycloak como administrador
2. Atualiza a realm `task-controller` com novo tempo de vida
3. Atualiza o client `task-app` com novo tempo de vida

### Método 3: API REST do Keycloak (Manual)

Se preferir fazer via curl:

```bash
# 1. Obter token de admin
ADMIN_TOKEN=$(curl -s -X POST \
  "http://localhost:8080/realms/master/protocol/openid-connect/token" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "username=admin&password=admin&client_id=admin-cli&grant_type=password" \
  | jq -r '.access_token')

# 2. Atualizar realm
curl -X PUT \
  "http://localhost:8080/admin/realms/task-controller" \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "accessTokenLifespan": 7200,
    "refreshTokenLifespan": 2592000,
    "offlineSessionIdleTimeout": 2592000
  }'
```

## Tempo de Vida Configurado

| Componente | Duração | Segundos |
|-----------|---------|----------|
| Access Token | 2 horas | 7200 |
| Refresh Token | 30 dias | 2592000 |
| Offline Session | 30 dias | 2592000 |

## Verificação

Para verificar se a configuração foi aplicada:

1. **No Keycloak Admin Console:**
   - Vá para http://localhost:8080/admin/master/console
   - Login com admin/admin
   - Selecione realm "task-controller"
   - Vá para Realm Settings > Tokens
   - Verifique "Access Token Lifespan" = 7200 segundos

2. **Via API:**
   ```bash
   curl "http://localhost:8080/admin/realms/task-controller" \
     -H "Authorization: Bearer $ADMIN_TOKEN" | jq '.accessTokenLifespan'
   ```

## Configuração no Laravel

O arquivo `.env` foi atualizado com:
```
KEYCLOAK_LEEWAY=7200
```

Isso significa que tokens expirados dentro de 2 horas após expiração ainda serão aceitos (margem de segurança).

## Arquivos Modificados

- `.env` - KEYCLOAK_LEEWAY aumentado para 7200
- `keycloak/import/realm-export.json` - Novo arquivo com configuração
- `app/Services/KeycloakTokenConfigService.php` - Serviço para configurar via API
- `app/Console/Commands/ConfigureKeycloakTokenLifetime.php` - Comando artisan

## Troubleshooting

**O token continua expirando rápido?**
- Verifique se o arquivo realm-export.json está em `keycloak/import/`
- Execute: `php artisan keycloak:configure-token-lifetime`
- Faça logout e login novamente para obter um novo token

**Erro ao executar comando artisan?**
- Certifique-se que o Keycloak está rodando
- Verifique as credenciais em `.env`: KEYCLOAK_ADMIN e KEYCLOAK_ADMIN_PASSWORD
- Verifique se a URL está correta: KEYCLOAK_BASE_URL_INTERNAL

