# 🚀 Guia de Migração - Campo Título

## ⚠️ Situação Atual

O container Docker `task_laravel` não foi encontrado. Isso significa que você precisa:

1. Iniciar os containers Docker
2. Executar a migração no container

---

## ✅ Passo 1: Iniciar os Containers Docker

### Comando:
```powershell
cd c:\MyDev\Projetos\task-controller
docker-compose up -d
```

**O que vai acontecer:**
- Inicia MySQL (port 3306)
- Inicia Keycloak (port 8080)
- Inicia Laravel App (container: task_app)
- Inicia Nginx (port 8000)

**Tempo estimado:** 30-60 segundos

---

## ✅ Passo 2: Verificar se os Containers Estão Rodando

### Comando:
```powershell
docker ps
```

**Você deve ver algo como:**
```
CONTAINER ID   IMAGE          PORTS
abc123        task_app
def456        task_nginx     0.0.0.0:8000->80/tcp
ghi789        task_mysql     0.0.0.0:3306->3306/tcp
jkl012        task_keycloak  0.0.0.0:8080->8080/tcp
```

---

## ✅ Passo 3: Executar a Migração

**IMPORTANTE:** O container se chama `task_app`, não `task_laravel`

### Comando Correto:
```powershell
docker exec task_app php artisan migrate
```

### O que vai aparecer:
```
Migration table created successfully.
  Migrating: 2026_03_02_162255_create_tasks_table
  Migrated:  2026_03_02_162255_create_tasks_table

  Migrating: 2026_03_02_000001_add_title_to_tasks_table
  Migrated:  2026_03_02_000001_add_title_to_tasks_table
```

✅ **Sucesso!** Todos os arquivos foram migrados corretamente.

---

## ✅ Passo 4: Verificar o Status das Migrações

### Comando:
```powershell
docker exec task_app php artisan migrate:status
```

### Você deve ver:
```
Batch  Migration                                      Batch Name
  1    2026_03_02_162255_create_tasks_table           Processed
  2    2026_03_02_000001_add_title_to_tasks_table     Processed
```

---

## ✅ Passo 5: Verificar se a Coluna foi Criada no Banco

### Opção A: Via MySQL
```bash
docker exec task_mysql mysql -u root -proot taskcontroller -e "DESCRIBE tasks;"
```

Você deve ver:
```
Field          Type
id             bigint
user_id        bigint
title          varchar(255)     ← NOVO
description    varchar(1000)
status         enum
created_at     timestamp
updated_at     timestamp
```

### Opção B: Via Laravel Tinker
```bash
docker exec task_app php artisan tinker
> DB::table('tasks')->first();
```

---

## ❌ Se Algo Der Erro

### Erro: "No such container: task_app"

**Solução:**
1. Verifique se Docker está rodando
2. Execute novamente: `docker-compose up -d`
3. Aguarde 30 segundos
4. Tente novamente

### Erro: "Connection refused" ou "Cannot connect to MySQL"

**Solução:**
1. Aguarde mais alguns segundos
2. Verifique se MySQL está pronto: `docker logs task_mysql`
3. Execute a migração novamente

### Erro: "Column 'title' already exists"

**Solução:**
- A migração já foi aplicada! Verifique no banco se a coluna existe.

---

## 🧪 Testando a Funcionalidade

### 1. Criar uma Nova Tarefa com Título

```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "title": "Minha Tarefa Importante",
    "description": "Descrição da tarefa",
    "status": "Em Planejamento"
  }'
```

### 2. Criar uma Tarefa SEM Título

```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "description": "Descrição da tarefa",
    "status": "Em Planejamento"
  }'
```

**Resultado esperado:** `title` será `(SEM TITULO)` automaticamente

### 3. Acessar a Interface Web

- **URL:** http://localhost:8000
- **Login:** Use suas credenciais do Keycloak
- **Vá para:** Minhas Tarefas
- **Crie/edite uma tarefa** e veja o novo campo de título

---

## 📝 Comandos Úteis

```bash
# Ver logs da migração
docker exec task_app php artisan migrate:status

# Ver logs do Laravel
docker logs task_app

# Desfazer a última migração (para testes)
docker exec task_app php artisan migrate:rollback

# Limpar cache
docker exec task_app php artisan cache:clear
docker exec task_app php artisan config:clear

# Reiniciar os containers
docker-compose restart
```

---

## 📊 Resumo do que foi Implementado

| Item | Descrição |
|------|-----------|
| **Coluna** | `title` (VARCHAR 255) |
| **Default** | `(SEM TITULO)` |
| **Tipo** | String, Opcional |
| **Máximo** | 255 caracteres |
| **Visível em** | Criar, Editar, Listar tarefas |

---

## ✅ Próximas Etapas

1. ✓ Inicie os containers: `docker-compose up -d`
2. ✓ Execute a migração: `docker exec task_app php artisan migrate`
3. ✓ Verifique o status: `docker exec task_app php artisan migrate:status`
4. ✓ Teste a aplicação em http://localhost:8000
5. ✓ Crie/edite tarefas com títulos

---

**Qualquer dúvida ou erro? Verifique os logs:**
```bash
docker logs task_app
docker logs task_mysql
docker logs task_nginx
```

