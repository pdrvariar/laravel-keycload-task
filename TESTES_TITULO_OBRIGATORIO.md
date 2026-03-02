# Testes para Validação Obrigatória de Título

## 🧪 Testes Manuais

### 1. Teste de Criação de Tarefa (User)

**Cenário 1: Sem Título**
```
1. Ir para: /tasks/create
2. Deixar o campo "Título da Tarefa" em branco
3. Preencher descrição e status
4. Clicar em "Criar Tarefa"

Resultado Esperado:
❌ Erro exibido: "O título é obrigatório. Por favor, informe um título para a tarefa."
❌ Formulário não é enviado
```

**Cenário 2: Com Título Válido**
```
1. Ir para: /tasks/create
2. Preencher: Título = "Minha Tarefa"
3. Preencher descrição e status
4. Clicar em "Criar Tarefa"

Resultado Esperado:
✅ Tarefa criada com sucesso
✅ Redirecionado para lista de tarefas
✅ Tarefa aparece com o título preenchido
```

**Cenário 3: Título com > 255 caracteres**
```
1. Ir para: /tasks/create
2. Preencher título com 256+ caracteres
3. Clicar em "Criar Tarefa"

Resultado Esperado:
❌ Erro exibido: "O título não pode exceder 255 caracteres."
❌ Formulário não é enviado
```

---

### 2. Teste de Edição de Tarefa (User)

**Cenário 1: Remover Título**
```
1. Ir para: /tasks/{id}/edit
2. Limpar o campo de título
3. Clicar em "Salvar Alterações"

Resultado Esperado:
❌ Erro exibido: "O título é obrigatório. Por fazer, informe um título para a tarefa."
❌ Alterações não são salvas
```

**Cenário 2: Editar Título Válido**
```
1. Ir para: /tasks/{id}/edit
2. Mudar título para: "Nova Tarefa"
3. Clicar em "Salvar Alterações"

Resultado Esperado:
✅ Tarefa atualizada com sucesso
✅ Redirecionado para lista de tarefas
✅ Novo título é exibido
```

---

### 3. Teste de Edição no Admin

**Cenário 1: Modal de Edição - Sem Título**
```
1. Ir para: /admin/tasks
2. Clicar no ícone de edição de uma tarefa
3. Limpar o campo de título
4. Clicar em "Salvar"

Resultado Esperado:
❌ Erro exibido: "O título é obrigatório. Por favor, informe um título para a tarefa."
❌ Modal permanece aberto
```

**Cenário 2: Modal de Edição - Com Título Válido**
```
1. Ir para: /admin/tasks
2. Clicar no ícone de edição de uma tarefa
3. Mudar o título para: "Tarefa Editada Admin"
4. Clicar em "Salvar"

Resultado Esperado:
✅ Modal fecha
✅ Alerta de sucesso: "Tarefa atualizada com sucesso!"
✅ Tarefa é atualizada na tabela
```

---

### 4. Teste de Clonagem (Admin)

**Cenário 1: Clonar Sem Título**
```
1. Ir para: /admin/tasks
2. Clicar no ícone de clonagem de uma tarefa
3. Limpar o campo de novo título
4. Clicar em "Clonar"

Resultado Esperado:
❌ Alerta exibido: "O título é obrigatório. Por favor, informe um título para a tarefa clonada."
❌ Modal permanece aberto
```

**Cenário 2: Clonar Com Título Válido**
```
1. Ir para: /admin/tasks
2. Clicar no ícone de clonagem de uma tarefa
3. Modificar título para: "Cópia da Tarefa - Nova"
4. Clicar em "Clonar"

Resultado Esperado:
✅ Modal fecha
✅ Alerta de sucesso: "Tarefa clonada com sucesso!"
✅ Nova tarefa aparece na tabela com o novo título
✅ Nova tarefa tem status "Em Planejamento"
```

---

## 🧪 Testes de API (cURL)

### 1. Teste POST /api/tasks

**Sem Título (Deve falhar)**
```bash
curl -X POST "http://localhost:8000/api/tasks" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "description": "Descrição da tarefa",
    "status": "Em Planejamento"
  }'

Resultado Esperado:
{
  "success": false,
  "message": "Erro de validação",
  "errors": {
    "title": ["O título é obrigatório. Por favor, informe um título para a tarefa."]
  }
}
Status: 422
```

**Com Título Válido (Deve suceder)**
```bash
curl -X POST "http://localhost:8000/api/tasks" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Nova Tarefa",
    "description": "Descrição da tarefa",
    "status": "Em Planejamento"
  }'

Resultado Esperado:
{
  "success": true,
  "message": "Tarefa criada com sucesso!",
  "data": {
    "id": 1,
    "user_id": 1,
    "title": "Nova Tarefa",
    "description": "Descrição da tarefa",
    "status": "Em Planejamento",
    "created_at": "2026-03-02T10:00:00.000000Z",
    "updated_at": "2026-03-02T10:00:00.000000Z"
  }
}
Status: 201
```

---

### 2. Teste PUT /api/tasks/{id}

**Sem Título (Deve falhar)**
```bash
curl -X PUT "http://localhost:8000/api/tasks/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "description": "Descrição atualizada",
    "status": "Em Andamento"
  }'

Resultado Esperado:
{
  "success": false,
  "message": "Erro de validação",
  "errors": {
    "title": ["O título é obrigatório. Por favor, informe um título para a tarefa."]
  }
}
Status: 422
```

**Com Título Válido (Deve suceder)**
```bash
curl -X PUT "http://localhost:8000/api/tasks/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Tarefa Atualizada",
    "description": "Descrição atualizada",
    "status": "Em Andamento"
  }'

Resultado Esperado:
{
  "success": true,
  "message": "Tarefa atualizada com sucesso!",
  "data": {
    "id": 1,
    "user_id": 1,
    "title": "Tarefa Atualizada",
    "description": "Descrição atualizada",
    "status": "Em Andamento",
    "created_at": "2026-03-02T10:00:00.000000Z",
    "updated_at": "2026-03-02T10:30:00.000000Z"
  }
}
Status: 200
```

---

## 📊 Teste de Integridade de Dados

**Verificar tarefas corrigidas no banco:**
```bash
php artisan tinker

# Contar tarefas com título padrão
>>> DB::table('tasks')->where('title', 'Sem Titulo - Corrigir')->count()

# Listar algumas tarefas corrigidas
>>> DB::table('tasks')->where('title', 'Sem Titulo - Corrigir')->limit(5)->get()

# Verificar se há alguma tarefa com título nulo (não deve haver)
>>> DB::table('tasks')->whereNull('title')->count()
0  // Deve ser zero

# Sair do Tinker
>>> exit()
```

---

## ✅ Checklist de Validação

### Frontend
- [ ] Campo título no formulário de criação é obrigatório (required)
- [ ] Campo título no formulário de edição é obrigatório (required)
- [ ] Campo título no modal de edição (admin) é obrigatório (required)
- [ ] Campo título no modal de clonagem (admin) é obrigatório (required)
- [ ] Validação JavaScript previne envio sem título
- [ ] Mensagem de erro é exibida quando título está vazio
- [ ] Mensagem de erro é exibida quando título > 255 caracteres
- [ ] Labels mostram asterisco vermelho "*" indicando obrigatório

### Backend
- [ ] Validação de título obrigatório em POST /api/tasks
- [ ] Validação de título obrigatório em PUT /api/tasks/{id}
- [ ] Mensagens de erro customizadas em português
- [ ] API retorna 422 quando título é inválido
- [ ] Campo title no banco de dados é NOT NULL
- [ ] Tarefas existentes foram corrigidas com "Sem Titulo - Corrigir"

### Banco de Dados
- [ ] Migration foi executada com sucesso
- [ ] Campo title é NOT NULL
- [ ] Nenhuma tarefa tem título nulo
- [ ] Tarefas antigas foram migradas corretamente

---

## 🐛 Solução de Problemas

**P: Recebo erro "SQLSTATE[HY000]: General error: 1 Cannot add a NOT NULL column with default value NULL"**
A: Isso significa que há tarefas com título NULL no banco. A migration deve corrigir isso. Se o erro persistir:
```bash
php artisan migrate:rollback
php artisan migrate
```

**P: Tarefas não estão sendo criadas**
A: Certifique-se de que:
1. O token JWT é válido
2. O usuário está autenticado
3. O header 'Authorization: Bearer TOKEN' está presente
4. O título está preenchido corretamente

**P: Modal de edição (admin) não valida título**
A: Verifique se o JavaScript foi carregado corretamente:
1. Inspecione o navegador (F12)
2. Veja console por erros
3. Confirme que a função saveEditTask() está disponível

---

## 📝 Notas

- Todas as validações são bidirecionais (frontend + backend)
- Mensagens de erro são customizadas em português
- A migração é reversível (rollback)
- Dados históricos são preservados com tag "Sem Titulo - Corrigir"

---

**Data:** 2 de Março de 2026
**Versão:** 1.0

