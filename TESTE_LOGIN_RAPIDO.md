# 🚀 Guia Rápido - Teste o Login Transparente

## ⚡ 5 Minutos para Testar

### 1️⃣ Verifique se o Keycloak está rodando
```bash
curl http://localhost:8080/realms/master
```

Se retornar JSON com informações do Keycloak, ✅ está ok.

### 2️⃣ Acesse a página de login
```
http://localhost:8000/login
```

Você deve ver:
- ✅ Campo de Email
- ✅ Campo de Senha
- ✅ Botão "Entrar"
- ✅ Texto "Credenciais padrão: admin / admin123"

### 3️⃣ Teste com Admin
```
Email: admin
Senha: admin123
```

Clique em "Entrar" e você deve ser:
- ✅ Redirecionado para `/admin/dashboard`
- ✅ Logado como admin

### 4️⃣ Teste Logout
Clique no botão de logout (se existir) e você deve voltar para `/login`

### 5️⃣ Teste Acesso Negado
Se tentar acessar `/admin/dashboard` sem estar logado:
- ✅ Será redirecionado para `/login`

---

## 🔍 Se Algo Não Funcionar

### Erro: "Email ou senha inválidos"

**Passo 1:** Verifique se Keycloak pode ser acessado
```bash
curl -v http://localhost:8080
```

**Passo 2:** Verifique se Direct Access Grants está ativado
```bash
curl -X POST \
  http://localhost:8080/realms/master/protocol/openid-connect/token \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'grant_type=password' \
  -d 'client_id=task-controller' \
  -d 'client_secret=sua-secret-aqui' \
  -d 'username=admin' \
  -d 'password=admin123'
```

Se retornar um token (JSON com "access_token"), ✅ está tudo certo.

Se retornar erro de "unsupported_grant_type":
1. Acesse Keycloak Admin Console
2. Vá para Clients → task-controller
3. Clique em "Capability config"
4. Ative "Direct Access Grants Enabled"
5. Clique "Save"

**Passo 3:** Verifique as variáveis de ambiente
```bash
# No diretório do Laravel
cat .env | grep KEYCLOAK
```

Deve mostrar algo como:
```
KEYCLOAK_BASE_URL=http://keycloak:8080
KEYCLOAK_REALM=master
KEYCLOAK_CLIENT_ID=task-controller
KEYCLOAK_CLIENT_SECRET=xxxxx
```

**Passo 4:** Limpe a cache e tente novamente
```bash
php artisan config:clear
php artisan cache:clear
```

### Erro: "Página em branco" ou "Erro 500"

Verifique os logs:
```bash
tail -50 storage/logs/laravel.log
```

Procure por mensagens de erro específicas.

### Não consegue acessar Keycloak do Laravel

Pode ser um problema de rede (Docker). Tente:

1. Se estiver usando Docker:
   - Use `KEYCLOAK_BASE_URL_INTERNAL=http://keycloak:8080`
   - E `KEYCLOAK_BASE_URL=http://localhost:8080` (para redirect do cliente)

2. Teste a conexão:
   ```bash
   docker exec -it task-controller-app curl http://keycloak:8080
   ```

---

## 📊 Fluxo Visual

```
┌─────────────────────────────────────┐
│ 1. Usuário acessa /login            │
└──────────────────┬──────────────────┘
                   ↓
┌─────────────────────────────────────┐
│ 2. Vê formulário com email/senha     │
└──────────────────┬──────────────────┘
                   ↓
┌─────────────────────────────────────┐
│ 3. Preenche: admin / admin123       │
└──────────────────┬──────────────────┘
                   ↓
┌─────────────────────────────────────┐
│ 4. Clica em "Entrar"                │
└──────────────────┬──────────────────┘
                   ↓
┌─────────────────────────────────────┐
│ 5. Laravel faz POST /login          │
└──────────────────┬──────────────────┘
                   ↓
        *** NOS BASTIDORES ***
┌─────────────────────────────────────┐
│ 6. Laravel comunica com Keycloak    │
│    grant_type=password              │
│    username=admin                   │
│    password=admin123                │
└──────────────────┬──────────────────┘
                   ↓
┌─────────────────────────────────────┐
│ 7. Keycloak retorna token           │
│    (com dados do usuário)           │
└──────────────────┬──────────────────┘
                   ↓
┌─────────────────────────────────────┐
│ 8. Laravel processa token           │
│    - Valida                         │
│    - Extrai dados                   │
│    - Cria sessão                    │
└──────────────────┬──────────────────┘
                   ↓
        *** FIM NOS BASTIDORES ***
┌─────────────────────────────────────┐
│ 9. Redireciona para /admin/dashboard│
│    (ou /dashboard se não for admin) │
└──────────────────┬──────────────────┘
                   ↓
┌─────────────────────────────────────┐
│ ✅ USUÁRIO LOGADO!                  │
└─────────────────────────────────────┘
```

---

## 🎯 Checklist de Funcionamento

Marque conforme testado:

- [ ] Keycloak está acessível em `http://localhost:8080`
- [ ] Página de login carrega em `http://localhost:8000/login`
- [ ] Campos de email e senha aparecem
- [ ] Mensagem "Credenciais padrão: admin / admin123" aparece
- [ ] Pode fazer login com admin/admin123
- [ ] Redirecionado para `/admin/dashboard` automaticamente
- [ ] Página do dashboard carrega
- [ ] Botão de logout existe
- [ ] Logout funciona e volta para `/login`
- [ ] Tenta acessar `/admin/dashboard` sem logar → redireciona para `/login`

---

## 🧪 Teste com cURL (Avançado)

Se quiser testar via CLI:

### 1. Obter token
```bash
TOKEN=$(curl -s -X POST \
  http://localhost:8080/realms/master/protocol/openid-connect/token \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'grant_type=password' \
  -d 'client_id=task-controller' \
  -d 'client_secret=seu-secret' \
  -d 'username=admin' \
  -d 'password=admin123' | jq -r '.access_token')

echo $TOKEN
```

### 2. Testar token no Laravel
```bash
curl -X GET \
  http://localhost:8000/admin/dashboard \
  -H "Cookie: XSRF-TOKEN=seu-token; laravel_session=seu-session"
```

---

## 💡 Dicas

### Se usar Docker Compose:

```bash
# Checar logs do Laravel
docker-compose logs -f laravel

# Checar logs do Keycloak
docker-compose logs -f keycloak

# Entrar no container do Laravel
docker-compose exec laravel bash

# Dentro do container, teste:
curl http://keycloak:8080/realms/master
```

### Se usar localhost diretamente:

```bash
# Limpe cache
php artisan config:clear
php artisan cache:clear

# Inicie servidor
php artisan serve

# Em outro terminal, teste
curl http://localhost:8000/login
```

---

## ✅ Tudo Funcionando!

Se passou em todos os testes, parabéns! 🎉

Seu sistema de login transparente está **100% operacional**.

---

**Próximas etapas:**

1. ✅ Personalizar a página de login (cores, logo, textos)
2. ✅ Criar mais usuários de teste no Keycloak
3. ✅ Adicionar mais features (recuperar senha, 2FA, etc)
4. ✅ Monitorar logs em produção

---

**Dúvidas?** Consulte `AUTENTICACAO_TRANSPARENTE.md` para mais detalhes.

