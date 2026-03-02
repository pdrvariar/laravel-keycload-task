# ✅ Correção do Erro: Keycloak Public Key

## 🔴 Erro Original
```
KeycloakGuard\Token::decode(): Argument #2 ($publicKey) must be of type string, null given
```

## 🎯 Causa do Problema
A chave pública do Keycloak (`KEYCLOAK_REALM_PUBLIC_KEY`) estava faltando no arquivo `.env`, fazendo com que o Laravel Keycloak Guard não conseguisse validar os tokens JWT ao criar tarefas via API.

## ✅ Solução Aplicada

### 1. Chave Pública Adicionada ao `.env`
A seguinte linha foi adicionada ao arquivo `laravel/.env`:

```env
KEYCLOAK_REALM_PUBLIC_KEY="MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxQ9kQ1jdR3H8tDUxnPP6ah4dFINQ1tFBArZiumH7kH9TDNhi1H1WudVTjdtpA5aGmuWo9Fk8V5NVZ5wvg5SksnNvDhcLAvwwh3CvRyU4aqY91jRMHOe94ck+VTXZJsAsHgIn5gpn3J+mV1Cvcp9oI/oN4lF9geQsWColotjw+pnbSqi6CJxJZYpAXE4/7j8NA0zSqirPfhfpTafaw8pYdWDNuAlAHwisXmDSTGk94wNBecxO9FQt7/kbM8KvvN6p9eVmRAARbVlkf8mBWENddfjdpvJkJJDcmiyKB/L5rQWk7ydLc1PgFUk7/Sj0KP/UtspLcq42xnL8r7QhaVIVwwIDAQAB"
```

### 2. Containers Reiniciados
Os containers foram reiniciados para carregar a nova configuração:
```bash
docker compose down
docker compose up -d
```

## 🧪 Como Testar

### Teste 1: Verificar a configuração
```bash
docker compose exec app php artisan tinker --execute="echo config('keycloak.realm_public_key') ? 'OK' : 'ERRO';"
```

Deve exibir: `OK`

### Teste 2: Criar uma tarefa via API

1. **Faça login** no sistema via navegador em http://localhost:8000/login
2. **Abra o DevTools** (F12) > Console
3. **Execute o seguinte código JavaScript** para criar uma tarefa:

```javascript
fetch('/api/tasks', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + sessionStorage.getItem('access_token')
    },
    body: JSON.stringify({
        title: 'Teste de Criação',
        description: 'Tarefa criada após correção da chave pública',
        status: 'pending'
    })
})
.then(r => r.json())
.then(data => console.log('Sucesso:', data))
.catch(err => console.error('Erro:', err));
```

4. Se o token não estiver no sessionStorage, pegue-o do cookie ou faça login via API primeiro.

### Teste 3: Usando curl (requer token válido)

```bash
# Primeiro, obtenha um token (substitua credenciais se necessário)
TOKEN=$(curl -s -X POST "http://localhost:8080/realms/task-controller/protocol/openid-connect/token" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "client_id=task-app" \
  -d "client_secret=XRugFZF5nv06ME45GvakdCs4l7Yrh7V5" \
  -d "grant_type=password" \
  -d "username=admin" \
  -d "password=admin123" | jq -r '.access_token')

# Crie uma tarefa
curl -X POST "http://localhost:8000/api/tasks" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "title": "Tarefa via API",
    "description": "Teste de criação com token válido",
    "status": "pending"
  }'
```

## 🔄 Se a Chave Pública Mudar no Futuro

Se você recriar o realm do Keycloak ou a chave pública mudar, siga estes passos:

### Método 1: Rota Helper (Mais Fácil)
1. Acesse: http://localhost:8000/setup/keycloak-key
2. Copie a chave exibida
3. Atualize o `.env` com a nova chave
4. Reinicie os containers: `docker compose restart app`

### Método 2: Via API do Keycloak
```bash
# Obtenha a chave diretamente do endpoint do realm
curl -s http://localhost:8080/realms/task-controller | jq -r '.public_key'
```

### Método 3: Via Admin Console
1. Acesse: http://localhost:8080/admin
2. Login: admin / admin
3. Selecione o realm `task-controller`
4. Vá em **Realm Settings** > **Keys**
5. Na linha do algoritmo **RS256**, clique em **Public key**
6. Copie a chave exibida

## 📝 Estrutura da Configuração

### Arquivo: `laravel/config/keycloak.php`
```php
'realm_public_key' => (function() {
    $key = env('KEYCLOAK_REALM_PUBLIC_KEY');

    if (empty($key)) {
        return null; // Permite comandos artisan sem a chave
    }

    // Adiciona delimitadores BEGIN/END se necessário
    if (strpos($key, '-----BEGIN PUBLIC KEY-----') === false) {
        $key = "-----BEGIN PUBLIC KEY-----\n" .
               wordwrap($key, 64, "\n", true) .
               "\n-----END PUBLIC KEY-----";
    }

    return $key;
})(),
```

### Como Funciona
1. O Laravel Keycloak Guard precisa da chave pública para **validar assinaturas JWT**
2. Quando uma requisição chega em `/api/tasks` (protegida por `auth:api`), o guard:
   - Extrai o token Bearer do header Authorization
   - Decodifica e valida a assinatura usando a chave pública
   - Se válido, autentica o usuário
   - Se inválido ou chave ausente, retorna erro 401

## 🚨 Problemas Comuns

### Erro: "Token expired"
- **Causa:** O token JWT expirou
- **Solução:** Obtenha um novo token fazendo login novamente

### Erro: "Invalid signature"
- **Causa:** A chave pública não corresponde à chave privada do Keycloak
- **Solução:** Atualize `KEYCLOAK_REALM_PUBLIC_KEY` com a chave correta

### Erro: "Unauthenticated"
- **Causa:** Token não enviado ou formato incorreto
- **Solução:** Certifique-se de enviar o header `Authorization: Bearer {token}`

## ✅ Verificação Final

Após aplicar a correção, execute:

```bash
# 1. Verificar se a chave está configurada
docker compose exec app php -r "echo getenv('KEYCLOAK_REALM_PUBLIC_KEY') ? 'Chave configurada!' : 'Chave AUSENTE!';"

# 2. Limpar cache do Laravel
docker compose exec app php artisan config:clear
docker compose exec app php artisan config:cache

# 3. Verificar logs para erros
docker compose logs app --tail 50
```

Se tudo estiver correto, você poderá criar tarefas via API sem erros! 🎉

