# 📋 PASSO A PASSO SIMPLES - APLICAR MIGRAÇÃO

## 4 PASSOS APENAS

### ⏱️ Tempo total: ~5 minutos

---

## PASSO 1️⃣: Abra o PowerShell

```
Teclas: Win + X → A (ou abra o PowerShell)
```

---

## PASSO 2️⃣: Inicie os Containers

Copie e cole este comando:

```powershell
cd c:\MyDev\Projetos\task-controller; docker-compose up -d
```

**Aguarde 30-60 segundos** (os containers estão iniciando)

---

## PASSO 3️⃣: Execute a Migração

Copie e cole este comando:

```powershell
docker exec task_app php artisan migrate
```

**Você verá mensagens como:**
```
Migrating: 2026_03_02_162255_create_tasks_table
Migrated:  2026_03_02_162255_create_tasks_table
Migrating: 2026_03_02_000001_add_title_to_tasks_table
Migrated:  2026_03_02_000001_add_title_to_tasks_table
```

---

## PASSO 4️⃣: Pronto! 🎉

Acesse a aplicação em: http://localhost:8000

- Faça login
- Vá para "Minhas Tarefas"
- Crie ou edite uma tarefa
- Use o novo campo **TÍTULO**

---

## ⚠️ Se der erro "No such container"

1. Aguarde 60 segundos (containers estão iniciando)
2. Tente novamente

---

## ✅ Pronto!

O campo TÍTULO está implementado e funcionando!

