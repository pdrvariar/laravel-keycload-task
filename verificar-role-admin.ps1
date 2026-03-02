# Script para verificar se a role admin está sendo detectada corretamente
# Simula a lógica de verificação de roles implementada no PHP

$jsonContent = @'
{
  "exp": 1772483439,
  "iat": 1772483139,
  "jti": "eacaf48f-aa45-4f86-8701-35c27a552de4",
  "iss": "http://localhost:8080/realms/task-controller",
  "aud": "account",
  "sub": "c50e14ec-95d5-4bdd-b570-24bc0bed94c2",
  "typ": "Bearer",
  "azp": "task-app",
  "session_state": "d4225d18-1822-4a9d-88b4-e5900a3b2cbd",
  "acr": "1",
  "allowed-origins": [
    ""
  ],
  "realm_access": {
    "roles": [
      "offline_access",
      "admin",
      "uma_authorization",
      "default-roles-task-controller"
    ]
  },
  "resource_access": {
    "account": {
      "roles": [
        "manage-account",
        "manage-account-links",
        "view-profile"
      ]
    }
  },
  "scope": "openid profile email",
  "sid": "d4225d18-1822-4a9d-88b4-e5900a3b2cbd",
  "email_verified": false,
  "roles": [
    "offline_access",
    "admin",
    "uma_authorization",
    "default-roles-task-controller"
  ],
  "name": "Pablo Rattes",
  "preferred_username": "administrador",
  "given_name": "Pablo",
  "family_name": "Rattes",
  "email": "administrador@example.com"
}
'@

$tokenData = $jsonContent | ConvertFrom-Json
$clientId = "task-app"

Write-Host "=== VERIFICAÇÃO DE ROLES ==="
Write-Host "Usuário: $($tokenData.email)"

# 1. Verificar roles do cliente (resource_access)
$clientRoles = @()
if ($tokenData.resource_access.$clientId) {
    $clientRoles = $tokenData.resource_access.$clientId.roles
}
Write-Host "Roles do Cliente ($clientId): $($clientRoles -join ', ')"

# 2. Verificar roles do realm (realm_access)
$realmRoles = @()
if ($tokenData.realm_access) {
    $realmRoles = $tokenData.realm_access.roles
}
Write-Host "Roles do Realm: $($realmRoles -join ', ')"

# 3. Combinar roles
$allRoles = $clientRoles + $realmRoles
Write-Host "Todas as Roles: $($allRoles -join ', ')"

# 4. Verificar se é admin
if ($allRoles -contains "admin") {
    Write-Host "RESULTADO: O usuário É um administrador (role 'admin' encontrada)." -ForegroundColor Green
} else {
    Write-Host "RESULTADO: O usuário NÃO É um administrador (role 'admin' não encontrada)." -ForegroundColor Red
}
