# Implementação do Campo "Título" nas Tarefas

## 📋 Resumo das Alterações

Foi adicionado um novo campo **`title`** (título) ao sistema de tarefas. Todas as tarefas existentes no banco de dados recebem automaticamente o título padrão **(SEM TITULO)**.

---

## ✅ Arquivos Modificados

### 1. **Migration - Adicionar Coluna Title**
📄 `laravel/database/migrations/2026_03_02_000001_add_title_to_tasks_table.php`

**Criada nova migration** que:
- Adiciona coluna `title` (VARCHAR 255) à tabela `tasks`
- Define valor padrão `(SEM TITULO)` para novas tarefas
- Atualiza tarefas existentes com o título padrão
- Permite reverter a alteração (método `down()`)

```php
Schema::table('tasks', function (Blueprint $table) {
    $table->string('title', 255)->default('(SEM TITULO)')->after('user_id');
});
```

---

### 2. **Model - Task.php**
📄 `laravel/app/Models/Task.php`

**Modificação:**
- Adicionado `'title'` ao array `$fillable`

```php
protected $fillable = [
    'user_id',
    'title',        // ← NOVO
    'description',
    'status',
];
```

---

### 3. **Controller - TaskController.php**
📄 `laravel/app/Http/Controllers/Api/TaskController.php`

**Modificações em 2 métodos:**

#### Método `store()` - Criar Tarefa
```php
$validated = $request->validate([
    'title' => 'nullable|string|max:255',  // ← NOVO
    'description' => 'required|string|max:1000',
    'status' => 'nullable|in:Em Planejamento,Em Andamento,Concluído,Pausado,Cancelado',
]);

$task = Task::create([
    'user_id' => $user->id,
    'title' => $validated['title'] ?? '(SEM TITULO)',  // ← NOVO
    'description' => $validated['description'],
    'status' => $validated['status'] ?? 'Em Planejamento',
]);
```

#### Método `update()` - Atualizar Tarefa
```php
$validated = $request->validate([
    'title' => 'nullable|string|max:255',  // ← NOVO
    'description' => 'required|string|max:1000',
    'status' => 'required|in:Em Planejamento,Em Andamento,Concluído,Pausado,Cancelado',
]);
```

---

### 4. **View - Criar Tarefa**
📄 `laravel/resources/views/tasks/create.blade.php`

**Modificações:**
- Adicionado campo de entrada para o título
- Atualizado JavaScript para enviar o título na requisição

```html
<!-- Novo campo de título -->
<div style="margin-bottom: 2rem;">
    <label>
        <i class="bi bi-bookmark"></i> Título da Tarefa
    </label>
    <input
        type="text"
        id="title"
        name="title"
        placeholder="Digite o título da tarefa..."
        maxlength="255"
    />
    <p>Opcional - Se deixar em branco, será definido como "(SEM TITULO)"</p>
</div>
```

```javascript
// JavaScript atualizado
body: JSON.stringify({
    title: title || '(SEM TITULO)',  // ← NOVO
    description,
    status
})
```

---

### 5. **View - Editar Tarefa**
📄 `laravel/resources/views/tasks/edit.blade.php`

**Modificações:**
- Adicionado campo de entrada para o título
- Atualizado JavaScript para carregar e salvar o título

```javascript
// Carregamento da tarefa
document.getElementById('title').value = task.title || '(SEM TITULO)';

// Salvamento
body: JSON.stringify({
    title: title || '(SEM TITULO)',  // ← NOVO
    description,
    status
})
```

---

### 6. **View - Listar Tarefas**
📄 `laravel/resources/views/tasks/index.blade.php`

**Modificações:**
- Adicionado campo título no modal de edição
- Atualizado renderização de cards para mostrar o título
- Atualizado JavaScript para manipular o título

```html
<!-- Modal com campo título -->
<div class="mb-3">
    <label class="form-label fw-bold">Título</label>
    <input type="text" class="form-control" id="taskTitle"
           placeholder="Título da tarefa" maxlength="255">
    <small class="text-muted">Opcional - Máximo 255 caracteres</small>
</div>
```

```javascript
// Renderização do card com título
<h5 class="card-title mb-2">
    <i class="bi bi-bookmark"></i> ${escapeHtml(task.title || '(SEM TITULO)')}
</h5>
```

---

## 🚀 Como Aplicar as Alterações

### Opção 1: Com Docker (Recomendado)

```bash
# 1. Certifique-se de que os containers estão rodando
cd c:\MyDev\Projetos\task-controller
docker-compose up -d

# 2. Aguarde 30-60 segundos para os containers iniciarem

# 3. Execute a migration (note: container é task_app, não task_laravel)
docker exec task_app php artisan migrate

# 4. Verifique o status
docker exec task_app php artisan migrate:status
```

### Opção 2: Sem Docker (Local)

```bash
# 1. Entre no diretório do Laravel
cd c:\MyDev\Projetos\task-controller\laravel

# 2. Execute a migration
php artisan migrate

# 3. Verifique o status
php artisan migrate:status
```

---

## 📊 Impacto das Alterações

### ✅ Compatibilidade Retroativa
- ✓ Tarefas existentes recebem automaticamente o título `(SEM TITULO)`
- ✓ Título é opcional ao criar/editar tarefas
- ✓ API continua funcionando sem o campo título (usa valor padrão)
- ✓ Nenhuma quebra de compatibilidade com código existente

### 🎨 Interface do Usuário
- ✓ Novo campo visível em todas as telas (criar, editar, listar)
- ✓ Cards de tarefas exibem o título em destaque
- ✓ Modal de edição rápida inclui o título
- ✓ Ícone de bookmark identifica visualmente o título

### 🔒 Validação
- ✓ Título opcional (nullable)
- ✓ Máximo 255 caracteres
- ✓ Valor padrão: `(SEM TITULO)`

---

## 🧪 Testando as Alterações

### 1. Criar Nova Tarefa
```bash
# Com título
POST /api/tasks
{
    "title": "Minha Tarefa Importante",
    "description": "Descrição detalhada",
    "status": "Em Planejamento"
}

# Sem título (usa padrão)
POST /api/tasks
{
    "description": "Descrição detalhada",
    "status": "Em Planejamento"
}
```

### 2. Atualizar Tarefa
```bash
PUT /api/tasks/{id}
{
    "title": "Título Atualizado",
    "description": "Descrição atualizada",
    "status": "Em Andamento"
}
```

### 3. Verificar no Banco de Dados
```sql
-- Ver todas as tarefas com títulos
SELECT id, title, description, status FROM tasks;

-- Contar tarefas sem título personalizado
SELECT COUNT(*) FROM tasks WHERE title = '(SEM TITULO)';
```

---

## 📝 Estrutura da Tabela Atualizada

```sql
tasks
├── id (bigint, primary key)
├── user_id (bigint, foreign key)
├── title (varchar 255, default: '(SEM TITULO)')  ← NOVO
├── description (varchar 1000)
├── status (enum)
├── created_at (timestamp)
└── updated_at (timestamp)
```

---

## 🎯 Próximos Passos Sugeridos

1. **Testar a aplicação:**
   - Criar novas tarefas com e sem título
   - Editar tarefas existentes
   - Verificar visualização na lista

2. **Validar migração:**
   - Verificar se a coluna foi criada corretamente
   - Confirmar que tarefas antigas têm o título padrão

3. **Melhorias futuras (opcional):**
   - Adicionar busca por título
   - Permitir ordenação por título
   - Adicionar validação de título único (se necessário)

---

## ⚠️ Observações Importantes

- ✓ **Backup:** Sempre faça backup do banco antes de rodar migrations em produção
- ✓ **Título Padrão:** Tarefas sem título aparecem como "(SEM TITULO)"
- ✓ **Campo Opcional:** Usuários podem deixar o título em branco
- ✓ **Limite:** Títulos têm limite de 255 caracteres

---

## 📞 Suporte

Se encontrar algum problema:
1. Verifique se a migration foi executada: `php artisan migrate:status`
2. Veja os logs do Laravel: `storage/logs/laravel.log`
3. Limpe o cache: `php artisan cache:clear && php artisan config:clear`

---

**Data da Implementação:** 02/03/2026
**Status:** ✅ Implementado e Pronto para Uso

