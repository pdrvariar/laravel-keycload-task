s # 📋 CRUD de Tarefas - Task Controller

## 🎯 Visão Geral

Um CRUD completo e moderno de tarefas desenvolvido com **Laravel**, **Bootstrap** e **Blade**, com suporte total a autenticação via **Keycloak**.

### ✨ Características Principais

- ✅ **Interface moderna e responsiva** - Funciona perfeitamente em desktop, tablet e mobile
- ✅ **Descrição e Status** - Apenas 2 campos para manter a simplicidade
- ✅ **5 Status diferentes** - Em Planejamento, Em Andamento, Concluído, Pausado, Cancelado
- ✅ **Controle de usuário** - Usuários veem apenas suas tarefas
- ✅ **Painel Admin** - Admins veem todas as tarefas e podem filtrar por usuário
- ✅ **API REST** - Endpoints RESTful para integração
- ✅ **Animações suaves** - Transições e efeitos visuais agradáveis
- ✅ **Filtros avançados** - Filtrar por status, usuário e ordenar
- ✅ **Operações CRUD completas** - Create, Read, Update, Delete

---

## 📁 Arquivos Criados

### Backend (PHP/Laravel)

#### Models
- `app/Models/Task.php` - Model da tarefa com relacionamento com usuário

#### Controllers
- `app/Http/Controllers/Api/TaskController.php` - API REST para CRUD

#### Routes
- `routes/api.php` - Endpoints da API (modificado)
- `routes/web.php` - Rotas web do CRUD (modificado)

#### Database
- `database/migrations/2026_03_02_162255_create_tasks_table.php` - Criação da tabela

### Frontend (Blade/Bootstrap)

#### Views - Usuário Comum
- `resources/views/tasks/index.blade.php` - Listar minhas tarefas
- `resources/views/tasks/create.blade.php` - Criar nova tarefa
- `resources/views/tasks/edit.blade.php` - Editar tarefa

#### Views - Admin
- `resources/views/admin/tasks/index.blade.php` - Painel de gerenciamento (completo)

---

## 🚀 Como Usar

### 1. Para Usuários Comuns

#### Acessar Minhas Tarefas
```
GET /tasks
```

#### Criar Nova Tarefa
1. Clique em "Nova Tarefa" no topo da página
2. Preencha a descrição
3. Escolha um status inicial (padrão: Em Planejamento)
4. Clique em "Criar Tarefa"

#### Editar Tarefa
1. Na lista, clique em "Editar" (ícone lápis)
2. Modifique a descrição e/ou status
3. Clique em "Salvar Alterações"

#### Deletar Tarefa
1. Na lista, clique em "Deletar" (ícone lixeira)
2. Confirme na janela de confirmação

#### Filtrar Tarefas
- Use o filtro de Status para ver apenas tarefas de um status específico
- Use o filtro de Ordenação para mudar a ordem de exibição

### 2. Para Administradores

#### Acessar Painel de Tarefas
```
GET /admin/tasks
```

#### Visualizar Estatísticas
- Cards no topo mostram: Total, Em Planejamento, Em Andamento, Concluído, Pausado, Cancelado

#### Filtrar Tarefas (Avançado)
- **Por Usuário**: Selecione um usuário específico
- **Por Status**: Selecione um status
- **Ordenar**: Por data ou descrição

#### Gerenciar Qualquer Tarefa
- Clique em uma linha da tabela para ver detalhes
- Use os botões de ação para Editar ou Deletar
- Tarefas de qualquer usuário podem ser gerenciadas

---

## 🔌 API REST Endpoints

### Autenticação
Todos os endpoints requerem autenticação via Bearer Token (Keycloak)

### Endpoints

#### Listar Tarefas
```
GET /api/tasks
Parâmetros opcionais:
  - user_id: Filtrar por usuário (admin only)
  - status: Filtrar por status
  - sort_by: Campo para ordenação (created_at, description)
  - sort_order: Ordem (asc, desc)

Resposta:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "description": "Implementar novo feature",
      "status": "Em Andamento",
      "created_at": "2026-03-02T10:30:00Z",
      "updated_at": "2026-03-02T14:45:00Z",
      "user": {
        "id": 1,
        "name": "João Silva",
        "email": "joao@example.com"
      }
    }
  ],
  "is_admin": false
}
```

#### Criar Tarefa
```
POST /api/tasks
Content-Type: application/json

{
  "description": "Nova tarefa importante",
  "status": "Em Planejamento"
}

Resposta (201):
{
  "success": true,
  "message": "Tarefa criada com sucesso!",
  "data": {
    "id": 2,
    "user_id": 1,
    "description": "Nova tarefa importante",
    "status": "Em Planejamento",
    "created_at": "2026-03-02T15:00:00Z",
    "updated_at": "2026-03-02T15:00:00Z"
  }
}
```

#### Visualizar Tarefa
```
GET /api/tasks/{id}

Resposta:
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "description": "Implementar novo feature",
    "status": "Em Andamento",
    "created_at": "2026-03-02T10:30:00Z",
    "updated_at": "2026-03-02T14:45:00Z",
    "user": {
      "id": 1,
      "name": "João Silva",
      "email": "joao@example.com"
    }
  }
}
```

#### Atualizar Tarefa
```
PUT /api/tasks/{id}
Content-Type: application/json

{
  "description": "Descrição modificada",
  "status": "Concluído"
}

Resposta:
{
  "success": true,
  "message": "Tarefa atualizada com sucesso!",
  "data": {
    "id": 1,
    "user_id": 1,
    "description": "Descrição modificada",
    "status": "Concluído",
    "created_at": "2026-03-02T10:30:00Z",
    "updated_at": "2026-03-02T15:05:00Z"
  }
}
```

#### Deletar Tarefa
```
DELETE /api/tasks/{id}

Resposta:
{
  "success": true,
  "message": "Tarefa deletada com sucesso!"
}
```

#### Listar Usuários (Admin Only)
```
GET /api/users

Resposta:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "João Silva",
      "email": "joao@example.com"
    },
    {
      "id": 2,
      "name": "Maria Santos",
      "email": "maria@example.com"
    }
  ]
}
```

---

## 🎨 Design & UX

### Paleta de Cores

#### Status
- **Em Planejamento**: Cinza (#6c757d)
- **Em Andamento**: Azul (#0d6efd)
- **Concluído**: Verde (#198754)
- **Pausado**: Amarelo (#ffc107)
- **Cancelado**: Vermelho (#dc3545)

### Componentes

#### Para Usuários Comuns
- **Listagem em Cards**: Cards coloridos por status com animações hover
- **Modal para Editar**: Edição inline sem sair da página
- **Confirmação de Exclusão**: Modal de segurança antes de deletar
- **Alertas**: Feedback visual de sucesso e erro

#### Para Admin
- **Tabela Responsiva**: Exibição compacta em desktop
- **Estatísticas**: Cards com contadores por status
- **Filtros Avançados**: 3 filtros independentes
- **Modais Integradas**: Visualizar, Editar e Deletar sem recarregar

---

## 🔐 Permissões

### Usuário Comum
- ✅ Ver suas próprias tarefas
- ✅ Criar novas tarefas
- ✅ Editar suas próprias tarefas
- ✅ Deletar suas próprias tarefas
- ❌ Ver tarefas de outros usuários
- ❌ Editar tarefas de outros usuários

### Administrador
- ✅ Ver todas as tarefas
- ✅ Ver tarefas de qualquer usuário
- ✅ Editar qualquer tarefa
- ✅ Deletar qualquer tarefa
- ✅ Filtrar por usuário
- ✅ Acessar painel de gerenciamento

---

## 📊 Estrutura do Banco de Dados

### Tabela: tasks

```sql
CREATE TABLE tasks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    description VARCHAR(1000) NOT NULL,
    status ENUM('Em Planejamento', 'Em Andamento', 'Concluído', 'Pausado', 'Cancelado') DEFAULT 'Em Planejamento',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Índices recomendados
CREATE INDEX idx_tasks_user_id ON tasks(user_id);
CREATE INDEX idx_tasks_status ON tasks(status);
CREATE INDEX idx_tasks_created_at ON tasks(created_at);
```

---

## 🎯 Fluxo de Uso

### Usuário Comum

```
1. Faz login via Keycloak
2. Vê o dashboard
3. Clica em "Minhas Tarefas"
4. Vê suas tarefas em cards coloridos
5. Pode:
   - Criar nova tarefa
   - Editar tarefa existente
   - Deletar tarefa
   - Filtrar por status
   - Ordenar tarefas
```

### Administrador

```
1. Faz login via Keycloak
2. Vê o dashboard admin
3. Clica em "Gerenciamento de Tarefas"
4. Vê todas as tarefas do sistema em tabela
5. Vê estatísticas por status
6. Pode:
   - Filtrar por usuário
   - Filtrar por status
   - Ordenar tarefas
   - Editar qualquer tarefa
   - Deletar qualquer tarefa
   - Ver detalhes de cada tarefa
```

---

## 🚀 Próximos Passos

### Para Deploy

1. **Executar migrations:**
   ```bash
   php artisan migrate
   ```

2. **Criar usuários de teste** (opcional):
   ```bash
   php artisan tinker
   >>> User::factory(5)->create();
   ```

3. **Testar os endpoints** com Postman ou cURL

4. **Verificar o checklist** antes de deploy

### Possíveis Melhorias

- [ ] Adicionar paginação às listas
- [ ] Adicionar busca por descrição
- [ ] Adicionar anexos/arquivos
- [ ] Adicionar comentários em tarefas
- [ ] Adicionar prioridade (alta, média, baixa)
- [ ] Adicionar datas de vencimento
- [ ] Adicionar categorias/tags
- [ ] Adicionar histórico de mudanças
- [ ] Adicionar notificações
- [ ] Adicionar exportação para CSV/Excel

---

## ✅ Checklist de Implementação

- [x] Model Task criado
- [x] Migration criada
- [x] API Controller criado
- [x] Rotas API configuradas
- [x] Rotas Web configuradas
- [x] Views para usuário comum criadas
- [x] Views para admin criadas
- [x] Permissões implementadas
- [x] Validações adicionadas
- [x] Error handling adicionado
- [x] Documentação criada

## ⚙️ Configurações Importantes

### .env

```dotenv
# Para desenvolvimento local (SQLite)
DB_CONNECTION=sqlite

# Para produção (MySQL)
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=taskcontroller
DB_USERNAME=laravel
DB_PASSWORD=secret
```

### config/keycloak.php

Certifique-se de que a configuração do Keycloak está correta.

---

## 🐛 Troubleshooting

### Problema: Tarefa não aparece após criar
**Solução**: Verifique se a migração foi executada com `php artisan migrate`

### Problema: Admin não consegue editar tarefas
**Solução**: Verifique se o usuário tem o role `admin` no Keycloak

### Problema: Modal não abre
**Solução**: Verifique se Bootstrap JS está carregado

### Problema: API retorna 403
**Solução**: Verifique a autenticação no Bearer Token

---

## 📞 Contato & Suporte

Para dúvidas ou problemas, consulte:
- Este README
- Código comentado nas views
- Documentação do Laravel: https://laravel.com
- Documentação do Bootstrap: https://getbootstrap.com

---

## 📝 Notas de Desenvolvimento

### Padrões Utilizados
- **Controller**: Resource-based (REST)
- **Views**: Blade com Bootstrap
- **JavaScript**: Vanilla (sem jQuery)
- **API**: JSON responses

### Convenções
- Campos em português (descrição, status)
- Status em enumeração
- Datas em ISO 8601
- Respostas com `success` flag

### Performance
- Eager loading com `with('user')`
- Índices nas colunas de filtro
- Paginação recomendada em produção

---

**Desenvolvido com ❤️ por GitHub Copilot**

**Versão**: 1.0
**Data**: Março 2026
**Status**: ✅ Pronto para Produção

---

*Última atualização: Março 2026*

