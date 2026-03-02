# 🎨 VISUALIZAÇÃO DO CAMPO TÍTULO

## Como Ficará a Interface

---

## 1️⃣ TELA DE CRIAR TAREFA

```
┌─────────────────────────────────────────────┐
│  ⬅️  Voltar para Tarefas                      │
│                                             │
│  ➕ Nova Tarefa                             │
│  Crie uma nova tarefa para sua lista        │
│                                             │
├─────────────────────────────────────────────┤
│                                             │
│  📘 Título da Tarefa                        │
│  ┌──────────────────────────────────────┐  │
│  │ Digite o título da tarefa...         │  │
│  └──────────────────────────────────────┘  │
│  Opcional - Se deixar em branco, será      │
│  definido como "(SEM TITULO)"              │
│                                             │
│  💬 Descrição da Tarefa                    │
│  ┌──────────────────────────────────────┐  │
│  │ Descreva sua tarefa em detalhes...   │  │
│  │                                      │  │
│  │                                      │  │
│  └──────────────────────────────────────┘  │
│  Máximo 1000 caracteres                    │
│                                             │
│  🏷️  Status Inicial                        │
│  ┌──────────────────────────┐              │
│  │ Em Planejamento ⏷        │              │
│  └──────────────────────────┘              │
│  Escolha o status inicial da sua tarefa    │
│                                             │
│  ┌─────────────┬─────────────────────────┐ │
│  │ ✕ Cancelar  │  ✓ Criar Tarefa        │ │
│  └─────────────┴─────────────────────────┘ │
│                                             │
└─────────────────────────────────────────────┘
```

---

## 2️⃣ TELA DE EDITAR TAREFA

```
┌─────────────────────────────────────────────┐
│  ⬅️  Voltar para Tarefas                      │
│                                             │
│  ✏️  Editar Tarefa                         │
│  Modifique os dados e status de sua tarefa │
│                                             │
├─────────────────────────────────────────────┤
│                                             │
│  📘 Título da Tarefa                        │
│  ┌──────────────────────────────────────┐  │
│  │ Implementar Dashboard                │  │
│  └──────────────────────────────────────┘  │
│                                             │
│  💬 Descrição da Tarefa                    │
│  ┌──────────────────────────────────────┐  │
│  │ Criar dashboard com gráficos de      │  │
│  │ desempenho e relatórios              │  │
│  └──────────────────────────────────────┘  │
│                                             │
│  🏷️  Status da Tarefa                      │
│  ┌──────────────────────────┐              │
│  │ Em Andamento ⏷           │              │
│  └──────────────────────────┘              │
│                                             │
│  📅 Criado em: 02/03/2026 às 10:30         │
│  🔄 Atualizado em: 02/03/2026 às 14:45     │
│                                             │
│  ┌─────────────┬─────────────────────────┐ │
│  │ ✕ Cancelar  │  ✓ Salvar Alterações    │ │
│  └─────────────┴─────────────────────────┘ │
│                                             │
│  🗑️  Deletar Tarefa                        │
│                                             │
└─────────────────────────────────────────────┘
```

---

## 3️⃣ TELA DE LISTAR TAREFAS

```
┌──────────────────────────────────────────────────────────┐
│                                                          │
│  📋 Minhas Tarefas                                       │
│  Gerencie todas as suas tarefas em um único lugar        │
│                    [+ Nova Tarefa]                       │
│                                                          │
├──────────────────────────────────────────────────────────┤
│                                                          │
│  Filtros e Busca:                                        │
│  ┌──────────────┬──────────────┬────────────────────┐   │
│  │ Filtro Status│ Ordenar por  │ Buscar Tarefa...   │   │
│  └──────────────┴──────────────┴────────────────────┘   │
│                                                          │
├──────────────────────────────────────────────────────────┤
│                                                          │
│  Estatísticas:                                           │
│  ┌───────────┬──────────┬──────────┬───────────┐        │
│  │ Total: 15 │ Andamento│Concluídas│ Pendentes │        │
│  │ 📋       │ ⏳       │ ✓       │ ⚠️       │        │
│  │    15     │    5     │    8     │     2     │        │
│  └───────────┴──────────┴──────────┴───────────┘        │
│                                                          │
├──────────────────────────────────────────────────────────┤
│                                                          │
│  ┌─────────────────────────────┐ ┌──────────────────┐  │
│  │ 📘 Implementar Dashboard     │ │ 📘 Revisar Código│  │
│  │ 🔵 Em Andamento              │ │ 🟢 Concluído     │  │
│  │                              │ │                  │  │
│  │ Criar dashboard com          │ │ Fazer code review│  │
│  │ gráficos de desempenho...    │ │ do projeto...    │  │
│  │                              │ │                  │  │
│  │ 📅 02/03/2026 10:30          │ │ 📅 01/03/2026    │  │
│  │ [✏️ Editar] [🗑️ Deletar]     │ │ [✏️ Editar]      │  │
│  └─────────────────────────────┘ │ [🗑️ Deletar]     │  │
│                                   └──────────────────┘  │
│                                                          │
│  ┌─────────────────────────────┐                        │
│  │ 📘 (SEM TITULO)              │  ← Título Padrão     │
│  │ 🟡 Pausado                   │                       │
│  │                              │                       │
│  │ Tarefa sem título definido...│                       │
│  │                              │                       │
│  │ 📅 28/02/2026 09:15          │                       │
│  │ [✏️ Editar] [🗑️ Deletar]     │                       │
│  └─────────────────────────────┘                        │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

---

## 4️⃣ MODAL DE EDIÇÃO RÁPIDA

```
╔════════════════════════════════════════════╗
║  ✏️  Editar Tarefa                         ║
╠════════════════════════════════════════════╣
║                                            ║
║  📘 Título                                 ║
║  ┌───────────────────────────────────────┐ ║
║  │ Minha Tarefa Importante               │ ║
║  └───────────────────────────────────────┘ ║
║  Opcional - Máximo 255 caracteres         ║
║                                            ║
║  💬 Descrição                              ║
║  ┌───────────────────────────────────────┐ ║
║  │ Descrição detalhada da tarefa...      │ ║
║  │                                       │ ║
║  └───────────────────────────────────────┘ ║
║                                            ║
║  🏷️  Status                                ║
║  ┌──────────────────────────┐             ║
║  │ Em Andamento ⏷           │             ║
║  └──────────────────────────┘             ║
║                                            ║
║  ┌──────────┬──────────────────────────┐  ║
║  │ Cancelar │  ✓ Salvar               │  ║
║  └──────────┴──────────────────────────┘  ║
║                                            ║
╚════════════════════════════════════════════╝
```

---

## 🎯 PRINCIPAIS MUDANÇAS

✅ **Campo Título** - Novo e visível em todas as telas
✅ **Ícone Bookmark** 📘 - Identifica o título visualmente
✅ **Valor Padrão** - "(SEM TITULO)" para tarefas sem título
✅ **Título em Destaque** - Aparece antes da descrição nos cards
✅ **Compatível** - Funciona com todas as operações (criar, editar, listar)

---

## 📝 EXEMPLO DE TAREFA COMPLETA

```json
{
  "id": 1,
  "user_id": 1,
  "title": "Implementar Dashboard",
  "description": "Criar dashboard com gráficos de desempenho",
  "status": "Em Andamento",
  "created_at": "2026-03-02T10:30:00Z",
  "updated_at": "2026-03-02T14:45:00Z"
}
```

---

**Pronto para usar! ✨**

