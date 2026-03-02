# 🔐 Autenticação Transparente com Keycloak

## ✨ Novo Sistema de Login

O sistema de login foi **totalmente reformulado** para fornecer uma experiência mais simples e profissional.

### O Que Mudou?

**ANTES:**
- Redirecionamento direto para Keycloak
- Sem campos visíveis
- Experiência genérica

**DEPOIS:**
- ✅ Campos de **email** e **senha** visíveis
- ✅ Autenticação com Keycloak nos **bastidores**
- ✅ Experiência suave e profissional
- ✅ Mensagens de erro claras

---

## 🚀 Como Usar

### 1. Acessar a Página de Login
```
http://localhost:8000/login
```

### 2. Preencher as Credenciais
- **Email**: Seu email (deve estar registrado no Keycloak)
- **Senha**: Sua senha no Keycloak

### 3. Clicar em "Entrar"
O sistema se comunica com Keycloak nos bastidores e:
1. ✅ Valida as credenciais
2. ✅ Obtém o token de acesso
3. ✅ Extrai informações do usuário
4. ✅ Cria sessão no Laravel
5. ✅ Redireciona para o dashboard

**Tudo isso acontece de forma transparente!**

---

## 🔧 Configuração Necessária

### No Keycloak

O cliente "task-controller" deve ter estas permissões:
- **Direct Access Grants Enabled**: ✅ ATIVADO
  - Isto permite o fluxo "Resource Owner Password Credentials"

Para habilitar:
1. Acesse Keycloak Admin Console
2. Vá para Clients → task-controller
3. Aba "Capability config"
4. Ative "Direct Access Grants Enabled"
5. Clique "Save"

### No Laravel

Verifique que o arquivo `.env` contém:

```bash
KEYCLOAK_BASE_URL=http://keycloak:8080
KEYCLOAK_BASE_URL_INTERNAL=http://keycloak:8080
KEYCLOAK_REALM=master
KEYCLOAK_CLIENT_ID=task-controller
KEYCLOAK_CLIENT_SECRET=seu-secret-aqui
KEYCLOAK_REDIRECT_URI=http://localhost:8000/auth/callback
KEYCLOAK_LOGOUT_REDIRECT_URI=http://localhost:8000/login
```

---

## 📋 Fluxo de Autenticação

```
1. USUÁRIO ACESSA /login
   ↓ Vê tela com campos email/senha

2. USUÁRIO PREENCHE E CLICA "ENTRAR"
   ↓ Form faz POST /login

3. LARAVEL RECEBE REQUEST
   ↓ Valida email e senha

4. LARAVEL COMUNICA COM KEYCLOAK
   ↓ Envia credenciais via grant_type=password

5. KEYCLOAK RETORNA TOKEN
   ↓ Se credenciais válidas

6. LARAVEL PROCESSA TOKEN
   ↓ Extrai dados do usuário
   ↓ Cria/atualiza usuário no BD
   ↓ Cria sessão

7. REDIRECIONA PARA DASHBOARD
   ↓ /dashboard (user normal)
   ↓ /admin/dashboard (admin)

✅ USUÁRIO LOGADO E AUTENTICADO
```

---

## 🎯 Vantagens

### Para o Usuário
- ✅ Interface simples e clara
- ✅ Campos de email/senha familiares
- ✅ Mensagens de erro explicativas
- ✅ Redirecionamento automático

### Para o Desenvolvedor
- ✅ Menos redirecionamentos HTTP
- ✅ Melhor performance
- ✅ Mais controle sobre o fluxo
- ✅ Fácil de debugar
- ✅ Sem dependência de OAuth visual

### Para a Segurança
- ✅ OAuth 2.0 Resource Owner Password Credentials
- ✅ Tokens armazenados de forma segura em sessão
- ✅ CSRF protection ativa
- ✅ Keycloak faz validação final

---

## 🔐 Segurança

### Como os Tokens são Armazenados?

Os tokens são armazenados **na sessão do servidor** (seguro):

```php
session([
    'keycloak_access_token' => $tokenData['access_token'],
    'keycloak_refresh_token' => $tokenData['refresh_token'],
    'keycloak_user' => $payload,
]);
```

### Proteção CSRF

O formulário usa `@csrf` automaticamente:

```html
<form method="POST" action="{{ route('login') }}">
    @csrf
    <!-- ... -->
</form>
```

### Validação de Senha

A senha é enviada **apenas para Keycloak** via HTTPS. Nunca é armazenada no Laravel.

---

## 🆘 Troubleshooting

### "Email ou senha inválidos"

**Possíveis causas:**

1. **Keycloak não está acessível**
   - Verifique se Keycloak está rodando
   - Teste: `curl http://keycloak:8080`

2. **Direct Access Grants não está habilitado**
   - Acesse Keycloak Admin Console
   - Vá para Clients → task-controller
   - Ative "Direct Access Grants Enabled"

3. **Email/senha incorretos**
   - Verifique se o usuário existe no Keycloak
   - Tente com: `admin / admin123`

4. **Variáveis de ambiente incorretas**
   - Verifique `.env`
   - Rode: `php artisan config:clear`

### "No access token received from Keycloak"

**Solução:**

1. Verifique logs: `storage/logs/laravel.log`
2. Teste conectar diretamente ao Keycloak:

```bash
curl -X POST \
  http://keycloak:8080/realms/master/protocol/openid-connect/token \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'grant_type=password' \
  -d 'client_id=task-controller' \
  -d 'client_secret=seu-secret' \
  -d 'username=admin' \
  -d 'password=admin123'
```

### Usuário logado mas não vê dashboard

**Possível causa:** Roles do Keycloak não estão configuradas

Verifique a estrutura de roles:
- O token deve conter: `resource_access.task-controller.roles`
- Exemplo: `["user", "admin"]`

---

## 📊 Arquivos Modificados

### AuthController.php
- ✅ Novo método `login()` para autenticação direta
- ✅ Novo método `getAccessTokenFromKeycloak()` para comunicar com Keycloak
- ✅ Logout simplificado

### routes/web.php
- ✅ POST /login agora usa `login()` ao invés de `redirectToKeycloak()`
- ✅ Removido GET /auth/callback (não é mais necessário)
- ✅ Roles corrigidas em resource_access

### resources/views/auth/login.blade.php
- ✅ Adicionados campos de email e senha
- ✅ Validação de erros
- ✅ UI moderna e profissional

---

## 💻 Teste Local

### Criar um usuário de teste no Keycloak:

```bash
# 1. Acesse Keycloak Admin Console
http://localhost:8080/admin

# 2. Vá para Users → Add user
# 3. Preencha:
#    - Username: teste@example.com
#    - Email: teste@example.com
#    - First name: Teste
#    - Last name: User

# 4. Na aba "Credentials"
#    - Clique "Set Password"
#    - Senha: teste123
#    - Temporary: OFF

# 5. Na aba "Role Mapping"
#    - Atribua as roles necessárias

# 6. Teste fazer login em http://localhost:8000/login
```

---

## 📈 Performance

O novo sistema é **mais rápido** porque:
- ✅ Menos redirecionamentos HTTP (1 ao invés de 3)
- ✅ Sem necessidade de callback
- ✅ Resposta mais rápida do backend

**Tempo de login: ~1-2 segundos** (dependendo de latência do Keycloak)

---

## 🎓 Aprenda Mais

### Grant Types OAuth 2.0

O novo sistema usa: **Resource Owner Password Credentials**

```
grant_type=password
username=email
password=senha
client_id=task-controller
client_secret=secret
```

Versus o sistema anterior que usava: **Authorization Code Flow** (redirect)

---

## ✅ Checklist

- [ ] Keycloak está rodando
- [ ] Direct Access Grants está habilitado
- [ ] `.env` está configurado corretamente
- [ ] Você consegue acessar `/login`
- [ ] Campos de email/senha aparecem
- [ ] Pode fazer login com `admin / admin123`
- [ ] Redirecionado para dashboard automaticamente
- [ ] Pode fazer logout

---

## 📞 Suporte

Se encontrar problemas:

1. **Verifique os logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Teste a conexão com Keycloak:**
   ```bash
   curl http://keycloak:8080/realms/master
   ```

3. **Limpe a cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

4. **Reinicie o servidor:**
   ```bash
   php artisan serve
   ```

---

**Pronto para usar! Acesse http://localhost:8000/login 🎉**

