# 🎨 GUIA DE CUSTOMIZAÇÃO - CRUD DE TAREFAS

## 🎯 Introdução

Este guia contém 10 exemplos práticos de customização do CRUD de tarefas.

---

## 1️⃣ MUDAR CORES DOS STATUS

**Arquivo**: `resources/views/tasks/index.blade.php`

Procure por `.status-badge.em-andamento` e mude as cores:

```css
.status-badge.em-andamento {
    background-color: #e0c3fc;  /* Roxo claro */
    color: #5d3a87;              /* Roxo escuro */
}
```

---

## 2️⃣ ADICIONAR CAMPO DE PRIORIDADE

**Passo 1**: Criar migration
```bash
php artisan make:migration add_priority_to_tasks_table
```

**Passo 2**: Adicionar campo à migration:
```php
$table->enum('priority', ['Baixa', 'Média', 'Alta'])->default('Média');
```

**Passo 3**: Atualizar Model e Controller

---

## 3️⃣ MUDAR TAMANHO DOS CARDS

**Arquivo**: `resources/views/tasks/index.blade.php`

Mude `col-lg-4` para `col-lg-3` ou `col-lg-6` conforme necessário.

---

## 4️⃣ ADICIONAR BUSCA POR TEXTO

Adicione um input de busca e filtre os dados com JavaScript:

```javascript
document.getElementById('searchInput').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const filtered = allTasksData.filter(task =>
        task.description.toLowerCase().includes(search)
    );
    renderTasks(filtered);
});
```

---

## 5️⃣ ADICIONAR MODO ESCURO

No `layout/app.blade.php`, adicione:

```blade
<html lang="pt-BR" data-bs-theme="auto">
```

---

## 📊 Resumo Rápido

| Item | Dificuldade | Tempo |
|------|-------------|-------|
| Cores | Fácil | 5 min |
| Prioridade | Médio | 30 min |
| Tamanho Cards | Fácil | 5 min |
| Busca | Médio | 20 min |
| Dark Mode | Médio | 20 min |

---

**Para mais detalhes, consulte o código comentado nas views!**

