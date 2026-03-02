# 🎯 Guia Rápido: Usando os Filtros do Dashboard

## 📍 Como Usar

### Passo 1: Acessar o Dashboard
Após fazer login, você estará no dashboard com a página de boas-vindas.

### Passo 2: Ver os Cards Estratégicos
Você verá 4 cards principais:

```
┌─────────────────────────────────────────────────────────┐
│                                                          │
│  ✅ Minhas Tarefas     ⏳ Em Andamento     ✓ Concluídas │
│  (Total)               (Ativas)           (Completas)   │
│                                                          │
│  📊 Taxa de Conclusão                                   │
│  (Percentual + Barra de Progresso)                      │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

### Passo 3: Clicar nos Cards

#### **Clique em "Minhas Tarefas"**
- ✋ O cursor muda para pointer
- 📍 Você é redirecionado para: `/tasks`
- 📋 **Resultado**: Ver TODAS as suas tarefas sem filtro

#### **Clique em "Em Andamento"**
- ✋ O cursor muda para pointer
- 📍 Você é redirecionado para: `/tasks?filter=Em Andamento`
- 📋 **Resultado**: Ver apenas tarefas com status "Em Andamento"

#### **Clique em "Concluídas"**
- ✋ O cursor muda para pointer
- 📍 Você é redirecionado para: `/tasks?filter=Concluído`
- 📋 **Resultado**: Ver apenas tarefas com status "Concluído"

#### **Não Clique em "Taxa de Conclusão"**
- 📊 Este card mostra apenas informações estatísticas
- 🚫 Não é clicável

## 🎨 Efeitos Visuais

Quando você passa o mouse sobre os cards clicáveis:

**Antes do Hover:**
```
┌──────────────────────┐
│ 📊 Meu Card          │
│ (Normal)             │
└──────────────────────┘
```

**Durante o Hover:**
```
        ⬆️ Eleva-se

    ┌──────────────────────┐
    │ 📊 Meu Card          │
    │ (Com Sombra Azul)    │
    └──────────────────────┘

Cursor muda para: ☝️
```

**Após Sair do Hover:**
```
┌──────────────────────┐
│ 📊 Meu Card          │
│ (Volta ao Normal)    │
└──────────────────────┘

Cursor muda para: ➜
```

## 📱 Página de Tasks após Filtro

Quando você clica em um card e é redirecionado para a página de tasks:

```
┌─────────────────────────────────────────────────┐
│  📋 Minhas Tarefas                              │
│  Gerencie todas as suas tarefas em um único lugar│
│                                                  │
│  [+ Nova Tarefa]                                │
├─────────────────────────────────────────────────┤
│                                                  │
│  Filtrar por Status: [ EM ANDAMENTO ▼ ]        │
│  Ordenar por: [ Data (Mais Recentes) ▼ ]       │
│  Buscar: [                        ]             │
│                                                  │
├─────────────────────────────────────────────────┤
│                                                  │
│  Total: 5    Em Andamento: 3    Concluídas: 2  │
│                                                  │
├─────────────────────────────────────────────────┤
│                                                  │
│  📌 Tarefa 1 [Em Andamento]  [✏️ 🗑️]           │
│  Descrição da tarefa...                         │
│  📅 01/03/2026 10:30                            │
│                                                  │
│  📌 Tarefa 2 [Em Andamento]  [✏️ 🗑️]           │
│  Descrição da tarefa...                         │
│  📅 28/02/2026 14:20                            │
│                                                  │
│  ...                                            │
│                                                  │
└─────────────────────────────────────────────────┘
```

**Repare:**
- ✅ O select "Filtrar por Status" já está preenchido com "Em Andamento"
- ✅ As tarefas exibidas são apenas as que correspondem ao filtro
- ✅ Você pode modificar o filtro ou a ordenação a qualquer momento

## 🔄 Removendo o Filtro

Na página de tasks, você pode:

1. **Remover o filtro** selecionando "-- Todos os Status --" no select
2. **Voltar ao dashboard** clicando em um link de navegação
3. **Aplicar novo filtro** selecionando outro status no select

## 💡 Dicas Úteis

| Ação | Resultado |
|------|-----------|
| Clicar "Minhas Tarefas" no dashboard | Ver todas as tarefas |
| Clicar "Em Andamento" no dashboard | Ver apenas tarefas em andamento |
| Clicar "Concluídas" no dashboard | Ver apenas tarefas concluídas |
| Clicar e arrastar um card | Nada acontece (não é arrastrável) |
| Clicar com botão direito em um card | Menu de contexto do navegador |
| Passar o mouse suavemente | Animação suave de elevação |

## 🐛 Troubleshooting

### Problema: Clicar no card não funciona
**Solução:**
- Certifique-se de estar autenticado (sessão ativa)
- Limpe o cache do navegador (Ctrl+Shift+Delete)
- Recarregue a página (F5)

### Problema: Filtro não está sendo aplicado
**Solução:**
- Verifique o console (F12 > Console) para mensagens de erro
- Certifique-se de que há tarefas com esse status
- Tente recarregar a página manualmente

### Problema: Cards não respondendo ao hover
**Solução:**
- Desabilite extensões do navegador (pode estar bloqueando CSS)
- Tente em outro navegador
- Verifique se o JavaScript está habilitado

## 📚 Referência Rápida

```javascript
// Função que executa ao clicar um card
goToTasks(status)

// Exemplos de uso:
goToTasks('')              // → /tasks (todas as tarefas)
goToTasks('Em Andamento')  // → /tasks?filter=Em Andamento
goToTasks('Concluído')     // → /tasks?filter=Concluído
```

---

**Versão**: 1.0
**Última Atualização**: 02/03/2026
**Status**: ✅ Pronto para Uso

