# 🆘 TROUBLESHOOTING - SOLUÇÃO DE PROBLEMAS

## Problema 1: "No such container: task_laravel"

### ❌ Erro Que Aparece
```
Error response from daemon: No such container: task_laravel
```

### ✅ Solução

O container se chama `task_app`, não `task_laravel`.

**CORRIJA:**
```powershell
# ❌ ERRADO
docker exec task_laravel php artisan migrate

# ✅ CORRETO
docker exec task_app php artisan migrate
```

---

## Problema 2: "Cannot connect to Docker daemon"

### ❌ Erro Que Aparece
```
error during connect: This error may indicate that the docker daemon is not running
```

### ✅ Solução

O Docker não está rodando.

**OPÇÃO 1: Iniciar Docker Desktop**
- Abra o Docker Desktop (procure no menu Iniciar)
- Aguarde até aparecer "Docker is running"
- Tente novamente

**OPÇÃO 2: Verificar se está rodando**
```powershell
docker --version
```

Se aparecer a versão, Docker está OK. Se não aparecer nada, inicie o Docker Desktop.

---

## Problema 3: "Column 'title' already exists"

### ❌ Erro Que Aparece
```
SQLSTATE[42S21]: Column already exists: 1060 Duplicate column name 'title'
```

### ✅ Solução

A migração já foi aplicada! Isso é bom! ✓

**Verifique se está funcionando:**
```powershell
docker exec task_app php artisan migrate:status
```

Deve aparecer:
```
Batch  Migration                                      Batch Name
  1    2026_03_02_162255_create_tasks_table           Processed
  2    2026_03_02_000001_add_title_to_tasks_table     Processed
```

---

## Problema 4: "Connection refused" ou "Connection timeout"

### ❌ Erro Que Aparece
```
SQLSTATE[HY000] [2002] Connection refused
SQLSTATE[HY000] [2003] Can't connect to MySQL server
```

### ✅ Solução

O MySQL não está pronto ainda.

**Aguarde e tente novamente:**
```powershell
# Aguarde 60 segundos
Start-Sleep -Seconds 60

# Tente novamente
docker exec task_app php artisan migrate
```

**Ou verifique os logs:**
```powershell
docker logs task_mysql
```

---

## Problema 5: "No such container: task_app"

### ❌ Erro Que Aparece
```
Error response from daemon: No such container: task_app
```

### ✅ Solução

Os containers não foram iniciados.

**PASSO 1: Inicie os containers**
```powershell
cd c:\MyDev\Projetos\task-controller
docker-compose up -d
```

**PASSO 2: Aguarde 60 segundos**
```powershell
Start-Sleep -Seconds 60
```

**PASSO 3: Verifique se estão rodando**
```powershell
docker ps
```

**PASSO 4: Tente a migração novamente**
```powershell
docker exec task_app php artisan migrate
```

---

## Problema 6: Página em branco ao acessar http://localhost:8000

### ❌ O que aparece
- Página em branco
- Erro 500
- "Application error"

### ✅ Solução

Limpe o cache do Laravel:

```powershell
docker exec task_app php artisan cache:clear
docker exec task_app php artisan config:clear
docker exec task_app php artisan view:clear
```

**Depois recarregue a página:**
```
http://localhost:8000
```

---

## Problema 7: Campo de título não aparece na interface

### ❌ O que acontece
- Cria uma tarefa mas o campo de título não aparece
- Edita uma tarefa mas não vê o campo de título

### ✅ Solução

Os arquivos PHP foram modificados mas o servidor precisa recarregar.

**OPÇÃO 1: Reinicie os containers**
```powershell
docker-compose restart app
Start-Sleep -Seconds 10
```

**OPÇÃO 2: Force recarregar a página**
- Pressione `Ctrl + Shift + R` (força recarregar)
- Limpe o cache do navegador

---

## Problema 8: Erro "The migration has already been published"

### ❌ Erro Que Aparece
```
The migration has already been published.
```

### ✅ Solução

Isso pode acontecer se houver conflito de migrations.

**Verificar migrations executadas:**
```powershell
docker exec task_app php artisan migrate:status
```

**Se ambas aparecerem com "Processed", está OK! ✓**

Se houver conflito, contate o suporte técnico.

---

## Problema 9: Cria tarefa mas não aparece na lista

### ❌ O que acontece
- Cria tarefa com sucesso (mensagem de sucesso aparece)
- Mas a tarefa não aparece na lista

### ✅ Solução

O token pode estar expirado.

**OPÇÃO 1: Recarregue a página**
```
F5 ou Ctrl + R
```

**OPÇÃO 2: Faça logout e login novamente**
- Clique em "Sair"
- Faça login novamente
- Crie uma nova tarefa

---

## Problema 10: "Unauthorized" ao criar/editar tarefa

### ❌ Erro Que Aparece
```
401 Unauthorized
```

### ✅ Solução

Você não está autenticado.

**SOLUÇÃO:**
1. Faça logout
2. Faça login novamente
3. Tente criar/editar a tarefa

**Se persistir:**
```powershell
# Verifique os logs do Keycloak
docker logs task_keycloak
```

---

## Checklist de Verificação Rápida

- [ ] Docker está rodando? (`docker ps` mostra containers)
- [ ] Containers iniciados? (`docker-compose up -d`)
- [ ] Migration executada? (`docker exec task_app php artisan migrate:status`)
- [ ] Aplicação acessível? (http://localhost:8000)
- [ ] Você está logado? (username/password visível)
- [ ] Campo de título aparece? (criar nova tarefa)

---

## Comandos Úteis para Debugging

### Ver status dos containers
```powershell
docker ps
docker ps -a  # mostra todos (parados e rodando)
```

### Ver logs
```powershell
docker logs task_app      # logs do Laravel
docker logs task_mysql    # logs do MySQL
docker logs task_nginx    # logs do Nginx
docker logs task_keycloak # logs do Keycloak
```

### Restart rápido
```powershell
docker-compose down
docker-compose up -d
Start-Sleep -Seconds 60
```

### Limpar tudo e reconstruir
```powershell
docker-compose down -v  # remove volumes também
docker-compose up -d
Start-Sleep -Seconds 60
docker exec task_app php artisan migrate
```

### Ver banco de dados
```powershell
# Entrar no MySQL
docker exec -it task_mysql mysql -u root -proot taskcontroller

# Ver tarefas
SELECT id, title, description, status FROM tasks;

# Ver estrutura da tabela
DESCRIBE tasks;
```

---

## Logs Importantes

### Ver último log do Laravel
```powershell
docker logs task_app --tail 50
```

### Seguir logs em tempo real
```powershell
docker logs -f task_app
```

### Logs do arquivo
```powershell
docker exec task_app cat storage/logs/laravel.log | tail -50
```

---

## Contato e Suporte

Se nenhuma solução funcionar:

1. **Colete informações:**
   ```powershell
   docker --version
   docker-compose --version
   docker ps -a
   docker logs task_app
   ```

2. **Verifique a documentação:**
   - IMPLEMENTACAO_TITULO_TAREFAS.md
   - GUIA_MIGRACAO_TITULO.md
   - PASSO_A_PASSO_SIMPLES.md

3. **Tente reconstruir tudo:**
   ```powershell
   docker-compose down -v
   docker-compose up -d
   Start-Sleep -Seconds 60
   docker exec task_app php artisan migrate
   ```

---

## Quick Fix - Solução Rápida para Problemas Comuns

```powershell
# 1. Reinicie Docker
docker-compose down
docker-compose up -d

# 2. Aguarde inicialização
Start-Sleep -Seconds 60

# 3. Limpe cache
docker exec task_app php artisan cache:clear
docker exec task_app php artisan config:clear

# 4. Execute migrations
docker exec task_app php artisan migrate

# 5. Verifique status
docker exec task_app php artisan migrate:status

# 6. Acesse a aplicação
# http://localhost:8000
```

---

**Se ainda tiver problema, abra uma issue ou entre em contato com o suporte! 🆘**

---

*Última atualização: 02/03/2026*

