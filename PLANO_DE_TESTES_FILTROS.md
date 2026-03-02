# 🧪 Plano de Testes: Filtros do Dashboard

## ✅ Testes Manuais

### 1️⃣ Teste: Card "Minhas Tarefas" - Sem Filtro

**Pré-requisitos:**
- Estar autenticado na aplicação
- Estar na página do dashboard

**Passos:**
1. Localizar o card "Minhas Tarefas" (com ícone 📋)
2. Passar o mouse sobre o card
3. Observar mudança de cursor para ☝️ (pointer)
4. Observar card elevar-se e receber sombra
5. Clicar no card

**Resultado Esperado:**
- ✓ Redirecionamento para `/tasks`
- ✓ Sem parâmetros na URL
- ✓ Todas as tarefas aparecem (sem filtro)
- ✓ Select "Filtrar por Status" está em "-- Todos os Status --"
- ✓ Console mostra: "Nenhum filtro na URL"

**Status**: [ ] Passou [ ] Falhou

---

### 2️⃣ Teste: Card "Em Andamento" - Filtro "Em Andamento"

**Pré-requisitos:**
- Estar autenticado na aplicação
- Estar na página do dashboard
- Ter pelo menos uma tarefa com status "Em Andamento"

**Passos:**
1. Localizar o card "Em Andamento" (com ícone ⏳)
2. Passar o mouse sobre o card
3. Observar mudança de cursor para ☝️ (pointer)
4. Observar card elevar-se e receber sombra
5. Clicar no card

**Resultado Esperado:**
- ✓ Redirecionamento para `/tasks?filter=Em%20Andamento`
- ✓ Parâmetro `filter` contém "Em Andamento"
- ✓ Apenas tarefas com status "Em Andamento" aparecem
- ✓ Select "Filtrar por Status" mostra "Em Andamento"
- ✓ Contador "Em Andamento" mostra o número correto
- ✓ Console mostra: "✓ Filtro encontrado na URL: Em Andamento"
- ✓ Console mostra: "✓ Filtro aplicado ao select"

**Status**: [ ] Passou [ ] Falhou

---

### 3️⃣ Teste: Card "Concluídas" - Filtro "Concluído"

**Pré-requisitos:**
- Estar autenticado na aplicação
- Estar na página do dashboard
- Ter pelo menos uma tarefa com status "Concluído"

**Passos:**
1. Localizar o card "Concluídas" (com ícone ✓)
2. Passar o mouse sobre o card
3. Observar mudança de cursor para ☝️ (pointer)
4. Observar card elevar-se e receber sombra
5. Clicar no card

**Resultado Esperado:**
- ✓ Redirecionamento para `/tasks?filter=Conclu%C3%ADdo`
- ✓ Parâmetro `filter` contém "Concluído" (codificado)
- ✓ Apenas tarefas com status "Concluído" aparecem
- ✓ Select "Filtrar por Status" mostra "Concluído"
- ✓ Contador "Concluídas" mostra o número correto
- ✓ Console mostra: "✓ Filtro encontrado na URL: Concluído"
- ✓ Console mostra: "✓ Filtro aplicado ao select"

**Status**: [ ] Passou [ ] Falhou

---

### 4️⃣ Teste: Efeitos Visuais do Card

**Pré-requisitos:**
- Estar no dashboard
- Navegador com suporte a CSS transitions

**Passos:**
1. Passar o mouse lentamente sobre um card clicável
2. Observar movimento suave do card para cima
3. Observar mudança na sombra (mais pronunciada)
4. Sair do card com o mouse
5. Observar retorno suave do card à posição original

**Resultado Esperado:**
- ✓ Movimento é suave (não instantâneo)
- ✓ Sombra é visível e suave
- ✓ Cursor muda para ☝️ ao passar
- ✓ Animação leva ~300ms
- ✓ Não há "pulos" ou comportamentos erráticos

**Status**: [ ] Passou [ ] Falhou

---

### 5️⃣ Teste: Card "Taxa de Conclusão" - NÃO Clicável

**Pré-requisitos:**
- Estar no dashboard

**Passos:**
1. Passar o mouse sobre o card "Taxa de Conclusão" (com ícone 📊)
2. Observar comportamento
3. Clicar no card

**Resultado Esperado:**
- ✓ Cursor NÃO muda para pointer
- ✓ Card NÃO apresenta efeito de hover
- ✓ Clique NÃO causa redirecionamento
- ✓ Permanece na mesma página

**Status**: [ ] Passou [ ] Falhou

---

### 6️⃣ Teste: Modificar Filtro na Página de Tasks

**Pré-requisitos:**
- Estar na página de tasks com um filtro aplicado (ex: `?filter=Em Andamento`)
- Ter tarefas com diferentes status

**Passos:**
1. Abrir o select "Filtrar por Status"
2. Selecionar um status diferente (ex: "Concluído")
3. Observar mudança nas tarefas exibidas

**Resultado Esperado:**
- ✓ URL muda para refletir novo filtro
- ✓ Tarefas são recarregadas imediatamente
- ✓ Apenas tarefas com novo status aparecem
- ✓ Console mostra novo carregamento

**Status**: [ ] Passou [ ] Falhou

---

### 7️⃣ Teste: Limpar Filtro na Página de Tasks

**Pré-requisitos:**
- Estar na página de tasks com um filtro aplicado

**Passos:**
1. Abrir o select "Filtrar por Status"
2. Selecionar "-- Todos os Status --"
3. Observar mudança nas tarefas exibidas

**Resultado Esperado:**
- ✓ URL retorna para `/tasks` (sem parâmetro filter)
- ✓ Tarefas são recarregadas imediatamente
- ✓ TODAS as tarefas aparecem
- ✓ Console mostra novo carregamento

**Status**: [ ] Passou [ ] Falhou

---

### 8️⃣ Teste: Voltar do navegador

**Pré-requisitos:**
- Estar em `/tasks` com filtro aplicado
- Ter visitado o dashboard antes

**Passos:**
1. Clicar no botão "Voltar" do navegador (←)
2. Observar navegação

**Resultado Esperado:**
- ✓ Retorna ao dashboard
- ✓ Dashboard mostra corretamente
- ✓ Sem erros no console

**Status**: [ ] Passou [ ] Falhou

---

### 9️⃣ Teste: Compartilhar URL com Filtro

**Pré-requisitos:**
- Ter uma URL com filtro: `/tasks?filter=Em Andamento`
- Ter outro navegador ou incógnito aberto

**Passos:**
1. Copiar URL com filtro: `/tasks?filter=Em Andamento`
2. Colar em novo navegador/janela incógnita
3. Acessar a URL
4. Fazer login se necessário

**Resultado Esperado:**
- ✓ Página carrega com filtro aplicado
- ✓ Select mostra o filtro correto
- ✓ Apenas tarefas com status aparecem
- ✓ Console mostra aplicação correta do filtro

**Status**: [ ] Passou [ ] Falhou

---

### 🔟 Teste: Console Logging

**Pré-requisitos:**
- Estar na página de tasks
- Console aberto (F12)

**Passos:**
1. Abrir a aba "Console" (F12)
2. Recarregar a página `/tasks` (sem filtro)
3. Observar logs
4. Ir para `/tasks?filter=Em Andamento`
5. Observar novos logs

**Resultado Esperado:**
- ✓ Mensagem de grupo: "🔍 DIAGNÓSTICO - Inicialização da Página de Tarefas"
- ✓ Log mostra verificação de elementos DOM
- ✓ Log mostra verificação de tokens
- ✓ Log mostra "3. Verificando parâmetros da URL..."
- ✓ Se filtro existir: "✓ Filtro encontrado na URL: ..."
- ✓ Se filtro não existir: "- Nenhum filtro na URL"
- ✓ Log de "loadTasks()" executado
- ✓ Nenhuma mensagem de erro (❌) no console

**Status**: [ ] Passou [ ] Falhou

---

## 🚨 Testes de Erro

### ❌ Teste: Token Expirado

**Pré-requisitos:**
- Token de sessão expirado
- Tentar acessar `/tasks?filter=Em Andamento`

**Resultado Esperado:**
- ✓ Mensagem: "Você não está autenticado"
- ✓ Redirecionamento para `/login` após 3 segundos
- ✓ Console mostra erro com detalhes

**Status**: [ ] Passou [ ] Falhou

---

### ❌ Teste: URL com Caracteres Inválidos

**Pré-requisitos:**
- Acessar `/tasks?filter=<script>alert('xss')</script>`

**Resultado Esperado:**
- ✓ Filtro não é aplicado
- ✓ Nenhum alerta JavaScript é executado
- ✓ Página mostra estado seguro

**Status**: [ ] Passou [ ] Falhou

---

## 📊 Resumo de Testes

| # | Teste | Status |
|---|-------|--------|
| 1 | Card "Minhas Tarefas" | [ ] ✓ [ ] ✗ |
| 2 | Card "Em Andamento" | [ ] ✓ [ ] ✗ |
| 3 | Card "Concluídas" | [ ] ✓ [ ] ✗ |
| 4 | Efeitos Visuais | [ ] ✓ [ ] ✗ |
| 5 | Card "Taxa de Conclusão" | [ ] ✓ [ ] ✗ |
| 6 | Modificar Filtro | [ ] ✓ [ ] ✗ |
| 7 | Limpar Filtro | [ ] ✓ [ ] ✗ |
| 8 | Voltar do Navegador | [ ] ✓ [ ] ✗ |
| 9 | Compartilhar URL | [ ] ✓ [ ] ✗ |
| 10 | Console Logging | [ ] ✓ [ ] ✗ |
| ❌ 11 | Token Expirado | [ ] ✓ [ ] ✗ |
| ❌ 12 | Caracteres Inválidos | [ ] ✓ [ ] ✗ |

**Testes Passados**: ___/12
**Testes Falhados**: ___/12
**Taxa de Sucesso**: ____%

---

## 📝 Notas do Testador

```
Data do Teste: _______________
Testador: ____________________
Navegador: ____________________
Versão do Navegador: __________
Sistema Operacional: __________
Observações Adicionais:
_________________________________
_________________________________
_________________________________
```

---

**Plano de Testes v1.0**
**Data**: 02/03/2026

