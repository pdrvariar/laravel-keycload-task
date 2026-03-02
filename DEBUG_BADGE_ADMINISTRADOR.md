# 🔍 DEBUG: Sessão Keycloak não mostra Badge Administrador

## ❌ Problema

O usuário `administrador@example.com` tem a role `admin` no Keycloak (confirmado), mas o badge de ADMINISTRADOR não aparece no header.

---

## 🧪 Passo 1: Debug da Sessão

Acesse esta URL para ver o que está na sessão:

```
http://localhost:8000/debug-session.php
```

Isso mostrará:
- ✓ Conteúdo completo da sessão
- ✓ Token JWT decodificado
- ✓ Estrutura de `resource_access`
- ✓ Roles disponíveis
- ✓ Diagnóstico do problema

---

## 🔧 Passo 2: Correção Aplicada

### Problema Identificado

A função `session('keycloak_user.resource_access.task-controller.roles')` do Laravel pode **não funcionar** corretamente com arrays profundamente aninhados.

### Solução Implementada

Modificado o arquivo `header.blade.php` para usar **acesso direto ao array**:

```php
// ANTES (pode falhar)
$roles = session('keycloak_user.resource_access.task-controller.roles') ?? [];

// DEPOIS (robusto)
$keycloakUser = session('keycloak_user');
if (is_array($keycloakUser)) {
    if (isset($keycloakUser['resource_access']['task-controller']['roles'])) {
        $roles = $keycloakUser['resource_access']['task-controller']['roles'];
    }
}
```

---

## 🚀 Passo 3: Testar a Correção

### 1. Limpar Cache

```powershell
cd laravel
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 2. Fazer Logout/Login

```
1. Clicar em "Sair" no sistema
2. Limpar cookies: Ctrl+Shift+Delete
3. Fechar navegador
4. Abrir novamente
5. Login: administrador@example.com
```

### 3. Verificar Header

O badge deve aparecer:
```
🛡️ ADMINISTRADOR (laranja/âmbar)
```

---

## 🔍 Passo 4: Verificar Logs

Os logs do Laravel agora mostram informações de debug:

```powershell
# Ver logs
tail -f laravel/storage/logs/laravel.log

# Ou no Windows
Get-Content laravel/storage/logs/laravel.log -Tail 50 -Wait
```

Procure por:
```
Header Debug: {
    "keycloak_user_exists": true,
    "roles_found": ["admin"],
    "email": "administrador@example.com"
}
```

---

## 🐛 Possíveis Causas do Problema

### Causa 1: Sessão Antiga em Cache
**Sintoma**: Badge não muda mesmo após relogin
**Solução**:
```powershell
php artisan cache:clear
# Limpar cookies no navegador
# Fazer logout/login
```

### Causa 2: Estrutura do Token Diferente
**Sintoma**: `resource_access` não existe ou está em outro lugar
**Solução**:
- Acessar `/debug-session.php`
- Verificar estrutura real do token
- Ajustar código se necessário

### Causa 3: Client ID Diferente no Keycloak
**Sintoma**: Roles estão em outro client
**Solução**:
- Verificar nome do client no Keycloak
- Pode ser diferente de "task-controller"
- Ajustar código se necessário

---

## 📊 Verificação Completa

### Checklist de Debug

- [ ] Acessar `/debug-session.php`
- [ ] Verificar se `keycloak_user` existe
- [ ] Verificar se `resource_access` existe
- [ ] Verificar nome do client (task-controller?)
- [ ] Verificar se `roles` array existe
- [ ] Verificar se "admin" está no array
- [ ] Limpar cache do Laravel
- [ ] Limpar cookies do navegador
- [ ] Fazer logout/login
- [ ] Verificar logs do Laravel
- [ ] Verificar se badge aparece

---

## 🔧 Script de Teste Rápido

Execute este script para limpar tudo e testar:

```powershell
# Limpar tudo
cd C:\MyDev\Projetos\task-controller\laravel
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Verificar logs
Write-Host "✓ Cache limpo" -ForegroundColor Green
Write-Host ""
Write-Host "Agora:" -ForegroundColor Yellow
Write-Host "1. Acesse: http://localhost:8000/debug-session.php" -ForegroundColor Cyan
Write-Host "2. Faça logout no sistema" -ForegroundColor Cyan
Write-Host "3. Limpe cookies (Ctrl+Shift+Delete)" -ForegroundColor Cyan
Write-Host "4. Faça login novamente" -ForegroundColor Cyan
Write-Host "5. Verifique o badge no header" -ForegroundColor Cyan
```

---

## 📝 Modificações Feitas

### Arquivo: `header.blade.php`

**Linha 23-50**: Modificado para usar acesso direto ao array + debug log

**Mudanças**:
1. ✅ Acesso robusto ao array da sessão
2. ✅ Verificação de existência em cada nível
3. ✅ Log de debug para diagnóstico
4. ✅ Fallback seguro se não encontrar roles

---

## 🎯 Resultado Esperado

Após seguir os passos acima, o header deve mostrar:

```
┌────────────────────────────────────────┐
│ Admin User                              │
│ administrador@example.com               │
│ [🛡️ ADMINISTRADOR] ← LARANJA/ÂMBAR    │
└────────────────────────────────────────┘
```

---

## 📞 Se Ainda Não Funcionar

### 1. Compartilhe a saída de `/debug-session.php`

Acesse e copie todo o conteúdo mostrado.

### 2. Verifique os logs

```powershell
Get-Content laravel/storage/logs/laravel.log -Tail 100
```

### 3. Verifique o token no Keycloak

- Admin Console → Realm Settings → Keys
- Verify Token em https://jwt.io
- Procurar por `resource_access`

---

## ✅ Arquivo de Debug Criado

```
laravel/public/debug-session.php
```

Acesse: `http://localhost:8000/debug-session.php`

---

**Status**: 🔧 Correção aplicada + Debug habilitado
**Próximo passo**: Acessar `/debug-session.php` e seguir os passos acima

---

**Data**: 02/03/2026
**Versão**: 2.1 (Debug + Fix)

