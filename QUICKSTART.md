# ⚡ QUICK START - 5 MINUTOS

## 🚀 Comece Agora

### 1️⃣ Iniciar Docker (30 segundos)

```bash
cd task-controller
docker-compose up -d
```

✅ Aguarde 30 segundos para as migrations executarem

### 2️⃣ Verificar se Funcionou (30 segundos)

```bash
# Ver status dos containers
docker-compose ps

# Todos devem estar "Up"
```

### 3️⃣ Acessar a Aplicação (30 segundos)

Abra no navegador:
```
http://localhost:8000
```

Você será redirecionado para `/login`

### 4️⃣ Fazer Login (1 minuto)

Use credenciais do Keycloak:
- **Username**: seu_usuario
- **Password**: sua_senha

Se é sua primeira vez, crie um usuário no Keycloak em:
```
http://localhost:8080
```

### 5️⃣ Começar a Usar (2 minutos)

Clique em um dos links:

**Se é usuário comum:**
```
http://localhost:8000/tasks
```
- Clique em "Nova Tarefa"
- Preencha a descrição
- Escolha um status
- Clique em "Criar Tarefa"

**Se é administrador:**
```
http://localhost:8000/admin/tasks
```
- Veja todas as tarefas do sistema
- Filtre por usuário ou status
- Edite ou delete qualquer tarefa

---

## 📍 URLs Importantes

| URL | Descrição |
|-----|-----------|
| http://localhost:8000 | Home (login) |
| http://localhost:8000/tasks | Minhas tarefas |
| http://localhost:8000/admin/tasks | Painel admin |
| http://localhost:8000/api/tasks | API de tarefas |

---

## ❓ Primeiras Dúvidas

### P: Como faço login?
**R:** Use credenciais do Keycloak. Se não tem usuário, crie em http://localhost:8080 (admin/admin)

### P: Por que vejo apenas minhas tarefas?
**R:** Usuários comuns veem apenas suas tarefas. Admins veem todas.

### P: Como sei se sou admin?
**R:** Se vê "Gerenciamento de Tarefas" em `/admin/tasks`, você é admin!

### P: Como criar uma tarefa?
**R:** Clique em "Nova Tarefa" no topo da página

### P: Como editar uma tarefa?
**R:** Clique no ícone de lápis no card ou na linha da tabela

### P: Como deletar uma tarefa?
**R:** Clique no ícone de lixeira, depois confirme

---

## 🔴 Se Algo der Errado

### Erro: "Connection refused"
```bash
# Aguardar mais
sleep 30
docker-compose restart laravel
```

### Erro: "Database error"
```bash
# Executar migrations manualmente
docker-compose exec laravel php artisan migrate --force
```

### Erro: "Page not found"
```bash
# Limpar cache
docker-compose exec laravel php artisan cache:clear
docker-compose exec laravel php artisan view:clear
```

### Erro: "Permission denied"
```bash
# Ajustar permissões
docker-compose exec laravel chown -R www-data:www-data /var/www
```

---

## 📚 Para Saber Mais

| Você quer... | Leia... |
|-------------|---------|
| Guia completo | CRUD_TASKS_README.md |
| Customizar cores | CUSTOMIZE_TASKS_GUIDE.md |
| Fazer deploy | DEPLOYMENT.md |
| Testar API | API_TEST.sh |
| Ver índice | INDEX_CRUD_TASKS.md |

---

## 🎯 Próximos Passos

✅ **Agora que o CRUD está rodando:**

1. Crie algumas tarefas
2. Teste editar e deletar
3. Filtre por status
4. Se for admin, veja o painel completo
5. Leia os documentos para customizações

---

## 🆘 Suporte Rápido

```bash
# Ver logs
docker-compose logs -f laravel

# Executar comando artisan
docker-compose exec laravel php artisan tinker

# Acessar banco
docker-compose exec mysql mysql -u laravel -psecret taskcontroller

# Reiniciar tudo
docker-compose restart
```

---

**Tudo pronto! Divirta-se! 🎉**

Próximo passo: Crie sua primeira tarefa em http://localhost:8000/tasks

