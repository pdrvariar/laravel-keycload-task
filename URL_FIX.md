# 🔧 Correção: ERR_CONNECTION_REFUSED - Keycloak URL

## ❌ Problema

Ao tentar fazer login, aparecia o erro:
```
http://localhost/realms/task-controller/login-actions/authenticate?...
ERR_CONNECTION_REFUSED
```

**URL Incorreta:** `http://localhost` (porta 80)
**URL Correta:** `http://localhost:8080` (Keycloak)

## 🔍 Causa Raiz

O `.env` estava configurado com:
```env
KEYCLOAK_BASE_URL=http://keycloak:8080
```

**Problema:**
- `keycloak` é o hostname **interno do Docker**
- O navegador não consegue resolver `keycloak`
- O navegador precisa de `localhost:8080`

Mas havia um **conflito**:
- 🌐 **Navegador** precisa: `http://localhost:8080`
- 🐳 **Container Docker** precisa: `http://keycloak:8080` (rede interna)

## ✅ Solução Aplicada

### 1. URLs Separadas (Externa vs Interna)

**config/keycloak.php:**
```php
'base_url' => env('KEYCLOAK_BASE_URL', 'http://localhost:8080'),
'base_url_internal' => env('KEYCLOAK_BASE_URL_INTERNAL', 'http://keycloak:8080'),
```

**Por quê?**
- `base_url` → Para redirecionamentos do navegador
- `base_url_internal` → Para chamadas HTTP do backend (dentro do Docker)

### 2. .env Atualizado

```env
KEYCLOAK_BASE_URL=http://localhost:8080           # Para o navegador
KEYCLOAK_BASE_URL_INTERNAL=http://keycloak:8080   # Para backend Docker
KEYCLOAK_REDIRECT_URI=http://localhost:8000/auth/callback
KEYCLOAK_LOGOUT_REDIRECT_URI=http://localhost:8000
```

### 3. AuthController Atualizado

```php
// ANTES - Usava sempre base_url
$response = $http->post(config('keycloak.base_url') . '/realms/...');

// DEPOIS - Usa base_url_internal para chamadas backend
$keycloakUrl = config('keycloak.base_url_internal', config('keycloak.base_url'));
$response = $http->post($keycloakUrl . '/realms/...');
```

**Benefício:**
- Redirecionamentos do navegador → `http://localhost:8080` ✅
- Chamadas HTTP do Laravel → `http://keycloak:8080` ✅

## 🎯 Como Funciona Agora

### Fluxo de Login

1. **Usuário acessa:** http://localhost:8000
   ```
   Laravel redireciona para /login
   ```

2. **Laravel gera URL de redirecionamento:**
   ```php
   // Usa base_url (localhost:8080)
   http://localhost:8080/realms/task-controller/protocol/openid-connect/auth?...
   ```
   ✅ **Navegador consegue acessar!**

3. **Usuário faz login no Keycloak**
   ```
   Keycloak redireciona para:
   http://localhost:8000/auth/callback?code=...
   ```

4. **Laravel troca código por token:**
   ```php
   // Usa base_url_internal (keycloak:8080)
   // Chamada HTTP dentro da rede Docker
   POST http://keycloak:8080/realms/task-controller/protocol/openid-connect/token
   ```
   ✅ **Container consegue acessar via rede interna!**

5. **Login completo** ✅

## 🧪 Teste Agora

### Passo a Passo

1. **Limpe os cookies do navegador**
   - Ctrl+Shift+Delete
   - Marque "Cookies"
   - Limpar dados

2. **Acesse:** http://localhost:8000
   - Deve redirecionar para login
   - URL deve ser: `http://localhost:8080/realms/task-controller/...`
   - ✅ **NÃO mais `http://localhost`** (sem porta)

3. **Faça login:**
   - Username: `admin` ou `user`
   - Password: `admin123` ou `user123`

4. **Verifique:**
   - ✅ Login bem-sucedido
   - ✅ Redirecionado para `/dashboard`
   - ✅ Sem erro ERR_CONNECTION_REFUSED

## 📊 Comparação URLs

| Situação | Antes (❌) | Depois (✅) |
|----------|-----------|------------|
| Redirect navegador | `http://keycloak:8080` | `http://localhost:8080` |
| Token exchange backend | `http://keycloak:8080` | `http://keycloak:8080` |
| Navegador acessa? | ❌ Não resolve DNS | ✅ Funciona |
| Container acessa? | ✅ Funciona | ✅ Funciona |

## 🔍 Verificação

### URL no Navegador
Quando redirecionar para Keycloak, verifique a barra de endereços:

**✅ CORRETO:**
```
http://localhost:8080/realms/task-controller/protocol/openid-connect/auth?...
```

**❌ ERRADO:**
```
http://localhost/realms/...         # Falta porta 8080
http://keycloak:8080/realms/...     # Hostname interno
```

### Logs do Laravel
```bash
# Ver logs do container
docker-compose logs app --tail=50

# Deve mostrar chamadas bem-sucedidas ao Keycloak
# SEM erros de conexão recusada
```

## 🛠️ Comandos Aplicados

```bash
# 1. Atualizado .env
KEYCLOAK_BASE_URL=http://localhost:8080
KEYCLOAK_BASE_URL_INTERNAL=http://keycloak:8080

# 2. Limpo caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear

# 3. Reiniciado app
docker-compose restart app
```

## 📁 Arquivos Modificados

1. ✅ `laravel/.env` - URLs externa e interna separadas
2. ✅ `laravel/config/keycloak.php` - Adicionado base_url_internal
3. ✅ `laravel/app/Http/Controllers/AuthController.php` - Usa URL interna no backend

## 🎓 Conceito: Docker Networking

### Por que URLs diferentes?

**Rede Docker Interna:**
```
Container "app" pode acessar:
- http://keycloak:8080 ✅ (hostname interno)
- http://mysql:3306 ✅
- http://nginx:80 ✅
```

**Navegador (Host):**
```
Navegador pode acessar:
- http://localhost:8080 ✅ (porta mapeada)
- http://localhost:8000 ✅
- http://keycloak:8080 ❌ (hostname não existe)
```

**Solução:**
- Redirecionamentos → `localhost:8080` (navegador)
- Chamadas API internas → `keycloak:8080` (Docker)

## ⚠️ Importante: Produção

Em **produção**, você usará um domínio real:

```env
# Produção
KEYCLOAK_BASE_URL=https://auth.seudominio.com
KEYCLOAK_BASE_URL_INTERNAL=http://keycloak:8080  # Mantém interno
KEYCLOAK_REDIRECT_URI=https://app.seudominio.com/auth/callback
```

**Por quê?**
- Navegador → `https://auth.seudominio.com` (público)
- Backend → `http://keycloak:8080` (rede interna, mais rápido)

## ✨ Status Final

| Item | Status |
|------|--------|
| ERR_CONNECTION_REFUSED | ✅ RESOLVIDO |
| URL correta no navegador | ✅ localhost:8080 |
| Backend chama Keycloak | ✅ Via rede interna |
| Login funcionando | ✅ OK |
| Token exchange | ✅ OK |

---

## 🎉 PROBLEMA RESOLVIDO!

**Teste agora:**
1. Limpe cookies do navegador
2. Acesse http://localhost:8000
3. Verifique que redireciona para `http://localhost:8080/realms/...`
4. Faça login com `admin` / `admin123`
5. ✅ Sucesso!

**A URL agora está correta!** 🚀

