# Guia de Diagnóstico: Header, Sidebar e Carregamento de Tarefas

## 📋 Resumo das Correções

Três problemas principais foram resolvidos:

1. **Header vazio** → Arquivo preenchido com logo, título e dados do usuário
2. **Sidebar vazio** → Menu Tarefas adicionado junto com outras opções
3. **Erro ao carregar tarefas** → Middleware de validação de token criado

---

## ✅ Verificação Rápida

### 1. Verificar se os arquivos foram preenchidos

```powershell
# No PowerShell:
$header = Get-Content C:\MyDev\Projetos\task-controller\laravel\resources\views\partials\header.blade.php
$header.Length  # Deve ser > 0

$sidebar = Get-Content C:\MyDev\Projetos\task-controller\laravel\resources\views\partials\sidebar.blade.php
$sidebar.Length  # Deve ser > 0

$footer = Get-Content C:\MyDev\Projetos\task-controller\laravel\resources\views\partials\footer.blade.php
$footer.Length  # Deve ser > 0
```

### 2. Verificar se o middleware está registrado

```powershell
$bootstrap = Get-Content C:\MyDev\Projetos\task-controller\laravel\bootstrap\app.php
if ($bootstrap -match 'validate\.keycloak\.token') {
    Write-Host "OK - Middleware registrado"
}
```

### 3. Verificar se o middleware está nas rotas da API

```powershell
$api = Get-Content C:\MyDev\Projetos\task-controller\laravel\routes\api.php
if ($api -match "middleware.*validate\.keycloak\.token") {
    Write-Host "OK - Middleware adicionado às rotas"
}
```

---

## 🧪 Testes de Funcionamento

### Teste 1: Verificar se o Header aparece

**Como:**
1. Acesse http://localhost:8000/login
2. Procure pela div com class `modern-header`
3. Verifique se tem:
   - Logo "Task Controller"
   - Texto "Rattes Factory"

**Se não aparecer:**
- Verifique o arquivo `header.blade.php`
- Certifique-se que está sendo incluído em `layouts/app.blade.php` com `@include('partials.header')`

---

### Teste 2: Verificar se o Sidebar aparece com menu "Tarefas"

**Como:**
1. Após fazer login, acesse http://localhost:8000/dashboard
2. Procure pela div com class `sidebar`
3. Verifique se tem as opções:
   - Dashboard
   - Tarefas (menu com ícone de lista)
   - Nova Tarefa

**Se não aparecer:**
- Verifique o arquivo `sidebar.blade.php`
- Certifique-se que está sendo incluído em `layouts/app.blade.php` com `@include('partials.sidebar')`
- Verifique a rota `tasks.index` em `routes/web.php`

---

### Teste 3: Verificar se as tarefas carregam

**Como:**
1. Após login, vá para o Dashboard
2. Verifique se as tarefas aparecem abaixo dos cards de estatísticas
3. Abra o DevTools do navegador (F12)
4. Vá para a aba "Network"
5. Procure pela requisição GET `/api/tasks`

**Se há erro "Erro ao carregar tarefas":**

```javascript
// No console do navegador, copie e execute:
fetch('/api/tasks', {
    headers: {
        'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content,
        'Accept': 'application/json'
    }
}).then(r => r.json()).then(d => console.log(d))
```

---

## 🔧 Solução de Problemas

### Problema: Header não aparece

**Causa possível:** Arquivo `header.blade.php` vazio
**Solução:**
```bash
# Verificar tamanho do arquivo
ls -la laravel/resources/views/partials/header.blade.php

# Se vazio (0 bytes), o arquivo precisa ser preenchido
```

### Problema: Sidebar não aparece

**Causa possível:** Arquivo `sidebar.blade.php` vazio
**Solução:**
```bash
# Verificar tamanho do arquivo
ls -la laravel/resources/views/partials/sidebar.blade.php

# Se vazio, o arquivo precisa ser preenchido
```

### Problema: "Erro ao carregar tarefas"

**Causas possíveis:**
1. Token não está sendo enviado
2. Token expirou
3. Guard keycloak não consegue validar o token
4. API retornando erro 401/403

**Passos de diagnóstico:**

1. **Verificar se o token está na sessão:**
```php
// Em tinker ou em um controller temporário:
dd(session('keycloak_access_token'));
```

2. **Testar a API diretamente:**
```bash
# Obter o token da sessão e testar a API
curl -H "Authorization: Bearer SEU_TOKEN_AQUI" \
     http://localhost:8000/api/tasks
```

3. **Verificar logs do Laravel:**
```bash
# Ver os logs em tempo real
tail -f laravel/storage/logs/laravel.log
```

4. **Verificar se a chave pública do Keycloak está configurada:**
```bash
# No arquivo .env, procure por:
KEYCLOAK_REALM_PUBLIC_KEY=
```

---

## 📁 Arquivos Modificados

| Arquivo | Status | Descrição |
|---------|--------|-----------|
| `laravel/resources/views/partials/header.blade.php` | ✅ Preenchido | Header com logo e dados do usuário |
| `laravel/resources/views/partials/sidebar.blade.php` | ✅ Preenchido | Menu com opção "Tarefas" |
| `laravel/resources/views/partials/footer.blade.php` | ✅ Preenchido | Footer com informações da empresa |
| `laravel/app/Http/Middleware/ValidateKeycloakToken.php` | ✅ Criado | Middleware para validar tokens |
| `laravel/routes/api.php` | ✅ Modificado | Adicionado middleware às rotas |
| `laravel/bootstrap/app.php` | ✅ Modificado | Registrado novo middleware |

---

## 🎯 Checklist Final

- [ ] Header aparece com título "Task Controller"
- [ ] Header mostra nome e email do usuário logado
- [ ] Sidebar aparece com menu "Tarefas"
- [ ] Sidebar tem opções: Dashboard, Tarefas, Nova Tarefa
- [ ] Footer aparece no rodapé
- [ ] Dashboard carrega as tarefas sem erro
- [ ] Ao clicar em "Tarefas" no sidebar, vai para a página de tarefas
- [ ] Botão "Sair" no header funciona

---

## 📞 Contato/Suporte

Se encontrar algum problema:
1. Verifique os logs: `laravel/storage/logs/laravel.log`
2. Abra o DevTools do navegador (F12) e veja se há erros no console
3. Verifique se o servidor Keycloak está rodando
4. Verifique se a chave pública está configurada em `.env`

