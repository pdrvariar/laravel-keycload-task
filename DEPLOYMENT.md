# 🚀 GUIA DE DEPLOYMENT - CRUD DE TAREFAS

## 📋 Conteúdo

1. [Requisitos](#requisitos)
2. [Como Iniciar](#como-iniciar)
3. [Verificação Pós-Deploy](#verificação-pós-deploy)
4. [Troubleshooting](#troubleshooting)
5. [URLs e Endpoints](#urls-e-endpoints)

---

## 📦 Requisitos

- Docker e Docker Compose instalados
- Porta 8000 disponível (Laravel)
- Porta 3306 disponível (MySQL)
- Porta 8080 disponível (Keycloak)

---

## 🚀 Como Iniciar

### Passo 1: Clonar/Preparar o Projeto

```bash
cd /caminho/para/task-controller
```

### Passo 2: Iniciar os Containers

```bash
docker-compose up -d
```

Isso irá:
1. ✅ Criar e iniciar o container MySQL
2. ✅ Criar e iniciar o container Laravel (com PHP-FPM)
3. ✅ Criar e iniciar o container Nginx
4. ✅ Criar e iniciar o container Keycloak
5. ✅ Executar automaticamente as migrations

### Passo 3: Aguardar a Inicialização Completa

```bash
# Ver logs do Laravel
docker-compose logs laravel

# Procure por: "✓ Laravel iniciado com sucesso!"
```

### Passo 4: Verificar se Tudo Funcionou

```bash
# Verificar containers rodando
docker-compose ps

# Todos devem estar com status "Up"
```

---

## ✅ Verificação Pós-Deploy

### 1. Testar Conexão com MySQL

```bash
docker-compose exec mysql mysql -u laravel -p -e "SELECT VERSION();"
# Senha: secret
```

**Resultado esperado:** Versão do MySQL exibida

### 2. Testar Aplicação Laravel

```bash
curl http://localhost:8000/
```

**Resultado esperado:** Redirecionamento para `/login`

### 3. Verificar Migrations

```bash
docker-compose exec laravel php artisan migrate:status
```

**Resultado esperado:** Todas as migrations com status "Ran"

### 4. Verificar Tabelas do Banco

```bash
docker-compose exec mysql mysql -u laravel -p taskcontroller -e "SHOW TABLES;"
# Senha: secret
```

**Resultado esperado:** Tabela `tasks` listada

### 5. Testar API

```bash
# Sem autenticação (deve retornar 401)
curl http://localhost:8000/api/tasks

# Com token válido do Keycloak
curl -H "Authorization: Bearer SEU_TOKEN" http://localhost:8000/api/tasks
```

---

## 🐛 Troubleshooting

### Problema: "Connection refused" ao conectar ao MySQL

**Solução:**

```bash
# Aguardar mais tempo
docker-compose logs mysql | tail -20

# Se MySQL ainda não iniciou, aguarde 30 segundos
sleep 30
docker-compose restart laravel
```

### Problema: Migrations não executaram

**Solução:**

```bash
# Executar manualmente
docker-compose exec laravel php artisan migrate --force

# Ver detalhes
docker-compose exec laravel php artisan migrate:status
```

### Problema: Erro ao acessar a aplicação

**Solução:**

```bash
# Ver logs do Laravel
docker-compose logs laravel

# Ver logs do Nginx
docker-compose logs nginx

# Limpar cache
docker-compose exec laravel php artisan cache:clear
docker-compose exec laravel php artisan view:clear
```

### Problema: Permissão negada nos arquivos

**Solução:**

```bash
# Ajustar permissões
docker-compose exec laravel chown -R www-data:www-data /var/www
docker-compose exec laravel chmod -R 755 /var/www
```

### Problema: Erro "SQLSTATE[HY000]"

**Solução:**

```bash
# Reiniciar containers
docker-compose restart

# Verificar variáveis de ambiente
docker-compose exec laravel php artisan tinker
>>> env('DB_HOST')
>>> env('DB_PORT')
>>> env('DB_DATABASE')
```

---

## 🔗 URLs e Endpoints

### Aplicação Web

| URL | Descrição |
|-----|-----------|
| http://localhost:8000 | Homepage (redireciona para login) |
| http://localhost:8000/login | Página de login |
| http://localhost:8000/dashboard | Dashboard (usuário comum) |
| http://localhost:8000/admin/dashboard | Dashboard admin |
| http://localhost:8000/tasks | Minhas tarefas |
| http://localhost:8000/tasks/create | Criar tarefa |
| http://localhost:8000/admin/tasks | Gerenciar tarefas (admin) |

### API Endpoints

| Método | URL | Descrição |
|--------|-----|-----------|
| GET | http://localhost:8000/api/tasks | Listar tarefas |
| POST | http://localhost:8000/api/tasks | Criar tarefa |
| GET | http://localhost:8000/api/tasks/{id} | Visualizar tarefa |
| PUT | http://localhost:8000/api/tasks/{id} | Atualizar tarefa |
| DELETE | http://localhost:8000/api/tasks/{id} | Deletar tarefa |
| GET | http://localhost:8000/api/users | Listar usuários (admin) |

### Serviços

| Serviço | URL | Credenciais |
|---------|-----|-------------|
| Laravel | http://localhost:8000 | N/A |
| MySQL | localhost:3306 | laravel / secret |
| Keycloak | http://localhost:8080 | admin / admin |
| Nginx | http://localhost:8000 | N/A |

---

## 📊 Estrutura do Banco de Dados

### Tabela: users

```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    keycloak_id VARCHAR(255) UNIQUE,
    password VARCHAR(255) NULLABLE,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Tabela: tasks

```sql
CREATE TABLE tasks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    description VARCHAR(1000) NOT NULL,
    status ENUM('Em Planejamento', 'Em Andamento', 'Concluído', 'Pausado', 'Cancelado') DEFAULT 'Em Planejamento',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);
```

---

## 🔐 Autenticação

### Fluxo de Autenticação

```
1. Usuário acessa http://localhost:8000/login
2. Clica em "Login com Keycloak"
3. Redireciona para http://localhost:8080
4. Faz login com credenciais Keycloak
5. Retorna para http://localhost:8000/dashboard
6. Token armazenado em sessão
```

### Obter Token Manualmente

```bash
# 1. Obter token
curl -X POST http://localhost:8080/realms/task-controller/protocol/openid-connect/token \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "client_id=task-app" \
  -d "client_secret=XRugFZF5nv06ME45GvakdCs4l7Yrh7V5" \
  -d "grant_type=password" \
  -d "username=seu_usuario" \
  -d "password=sua_senha"

# 2. Usar token
curl -H "Authorization: Bearer TOKEN_AQUI" http://localhost:8000/api/tasks
```

---

## 📈 Performance & Monitoramento

### Ver Logs em Tempo Real

```bash
# Todos os containers
docker-compose logs -f

# Apenas Laravel
docker-compose logs -f laravel

# Apenas MySQL
docker-compose logs -f mysql

# Apenas Nginx
docker-compose logs -f nginx
```

### Monitorar Uso de Recursos

```bash
# Ver estatísticas dos containers
docker stats

# Usar Ctrl+C para sair
```

### Executar Comandos Artisan

```bash
# Qualquer comando Laravel
docker-compose exec laravel php artisan COMANDO

# Exemplos:
docker-compose exec laravel php artisan tinker
docker-compose exec laravel php artisan cache:clear
docker-compose exec laravel php artisan db:seed
```

---

## 🔄 Operações Comuns

### Reiniciar Tudo

```bash
docker-compose restart
```

### Parar Tudo (Sem Apagar)

```bash
docker-compose stop
```

### Remover Tudo (Incluindo Dados)

```bash
docker-compose down -v
```

### Atualizar Aplicação

```bash
# Parar containers
docker-compose stop

# Atualizar código
git pull

# Reconstruir imagens
docker-compose build

# Reiniciar
docker-compose up -d
```

### Backup do Banco de Dados

```bash
# Exportar
docker-compose exec mysql mysqldump -u laravel -psecret taskcontroller > backup_tasks.sql

# Importar
docker-compose exec -T mysql mysql -u laravel -psecret taskcontroller < backup_tasks.sql
```

---

## 🚨 Verificação de Saúde

Crie um script `health-check.sh`:

```bash
#!/bin/bash

echo "Verificando saúde dos serviços..."
echo ""

# Laravel
echo -n "Laravel: "
curl -s http://localhost:8000/login > /dev/null && echo "✓ OK" || echo "✗ FALHA"

# MySQL
echo -n "MySQL: "
docker-compose exec -T mysql mysql -u laravel -psecret -e "SELECT 1;" > /dev/null 2>&1 && echo "✓ OK" || echo "✗ FALHA"

# Keycloak
echo -n "Keycloak: "
curl -s http://localhost:8080/auth/ > /dev/null && echo "✓ OK" || echo "✗ FALHA"

# Nginx
echo -n "Nginx: "
curl -s http://localhost:8000 > /dev/null && echo "✓ OK" || echo "✗ FALHA"

echo ""
echo "Verificação concluída!"
```

Execute com:

```bash
chmod +x health-check.sh
./health-check.sh
```

---

## 📝 Checklist Pós-Deploy

- [ ] Containers iniciados com sucesso
- [ ] Migrations executadas
- [ ] Tabela `tasks` existe no MySQL
- [ ] Aplicação acessível em http://localhost:8000
- [ ] Login funciona com Keycloak
- [ ] API responde em http://localhost:8000/api/tasks
- [ ] Dashboard acessível
- [ ] Admin pode gerenciar tarefas
- [ ] Usuário comum vê apenas suas tarefas
- [ ] CRUD funciona (Create, Read, Update, Delete)

---

## 🆘 Suporte

Para problemas adicionais:

1. Verifique os logs: `docker-compose logs`
2. Consulte o README: `CRUD_TASKS_README.md`
3. Veja o guia de customização: `CUSTOMIZE_TASKS.md`
4. Teste a API: `API_TEST.sh`

---

**Desenvolvido com ❤️ por GitHub Copilot**

**Versão**: 1.0 | **Data**: Março 2026 | **Status**: ✅ Pronto

---

*Última atualização: Março 2026*

