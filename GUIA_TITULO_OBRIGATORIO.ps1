#!/usr/bin/env pwsh
<#
.SYNOPSIS
    GUIA PASSO A PASSO - Título Obrigatório
.DESCRIPTION
    Instruções para validar e aplicar a validação obrigatória de título
#>

Write-Host "╔════════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║         VALIDAÇÃO OBRIGATÓRIA DE TÍTULO - GUIA COMPLETO       ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════════╝`n" -ForegroundColor Cyan

Write-Host "📋 O QUE FOI IMPLEMENTADO:" -ForegroundColor Cyan
Write-Host "✅ Campo TÍTULO agora é OBRIGATÓRIO em:" -ForegroundColor Green
Write-Host "   • Criação de Tarefa (User)" -ForegroundColor Green
Write-Host "   • Edição de Tarefa (User)" -ForegroundColor Green
Write-Host "   • Edição de Tarefa (Admin)" -ForegroundColor Green
Write-Host "   • Clonagem de Tarefa (Admin)" -ForegroundColor Green
Write-Host ""
Write-Host "✅ Validações em 3 níveis:" -ForegroundColor Green
Write-Host "   • Frontend: JavaScript bloqueia antes de enviar" -ForegroundColor Green
Write-Host "   • Backend: API rejeita dados inválidos" -ForegroundColor Green
Write-Host "   • Banco: Constraint NOT NULL impede erros" -ForegroundColor Green
Write-Host ""
Write-Host "✅ Mensagens claras em português`n" -ForegroundColor Green

Write-Host "🚀 PASSO 1: EXECUTAR A MIGRATION" -ForegroundColor Yellow
Write-Host @"
Isso criará o campo NOT NULL no banco de dados
e corrigirá tarefas sem título.

COMANDO:
cd C:\MyDev\Projetos\task-controller\laravel
php artisan migrate

" -ForegroundColor Gray

Write-Host "🔍 PASSO 2: VERIFICAR OS DADOS (OPCIONAL)" -ForegroundColor Yellow
Write-Host @"
Verifique quantas tarefas foram corrigidas.

COMANDO:
php artisan tinker

DEPOIS, no Tinker:
>>> DB::table('tasks')->where('title', 'Sem Titulo - Corrigir')->count()
>>> exit()

" -ForegroundColor Gray

Write-Host "🧪 PASSO 3: TESTAR NO NAVEGADOR" -ForegroundColor Yellow
Write-Host @"
Abra a aplicação e teste os cenários:

TESTE 1 - Criar SEM título:
  1. Vá para: /tasks/create
  2. Deixe título em BRANCO
  3. Preencha descrição e clique "Criar Tarefa"
  4. Resultado esperado: ❌ ERRO

TESTE 2 - Criar COM título:
  1. Vá para: /tasks/create
  2. Preencha: "Minha Tarefa"
  3. Preencha descrição e clique "Criar Tarefa"
  4. Resultado esperado: ✅ SUCESSO

TESTE 3 - Editar SEM título:
  1. Vá para: /tasks/{id}/edit
  2. LIMPE o campo de título
  3. Clique "Salvar Alterações"
  4. Resultado esperado: ❌ ERRO

TESTE 4 - Admin Clonar:
  1. Vá para: /admin/tasks
  2. Clique ícone de CLONAR
  3. Mude o título
  4. Clique "Clonar"
  5. Resultado esperado: ✅ SUCESSO

" -ForegroundColor Gray

Write-Host "📝 MENSAGENS DE ERRO ESPERADAS" -ForegroundColor Yellow
Write-Host @"
Quando o usuário tenta prosseguir sem título:

❌ "O título é obrigatório. Por favor,
   informe um título para a tarefa."

❌ "O título não pode exceder 255 caracteres."

" -ForegroundColor Red

Write-Host "📂 ARQUIVOS MODIFICADOS" -ForegroundColor Yellow
Write-Host @"
BACKEND (API e Banco):
  ✏️  laravel/app/Http/Controllers/Api/TaskController.php
  ✏️  laravel/database/migrations/2026_03_02_000001_add_title_to_tasks_table.php
  ✨ laravel/database/migrations/2026_03_02_make_title_required.php (NOVO)

FRONTEND (User):
  ✏️  laravel/resources/views/tasks/create.blade.php
  ✏️  laravel/resources/views/tasks/edit.blade.php

FRONTEND (Admin):
  ✏️  laravel/resources/views/admin/tasks/index.blade.php

DOCUMENTAÇÃO:
  ✨ IMPLEMENTACAO_TITULO_COMPLETA.md
  ✨ TESTES_TITULO_OBRIGATORIO.md
  ✨ RESUMO_VISUAL_TITULO_OBRIGATORIO.md
  ✨ TITULO_OBRIGATORIO_INICIO_RAPIDO.md

" -ForegroundColor Green

Write-Host "✅ CHECKLIST FINAL" -ForegroundColor Yellow
Write-Host @"
Antes de usar em produção:

☐ Migration foi executada sem erros
☐ Campo título é obrigatório na criação
☐ Campo título é obrigatório na edição
☐ Não é possível deixar título vazio
☐ Mensagens de erro aparecem corretamente
☐ API retorna erro 422 se título for inválido
☐ Tarefas antigas foram migradas corretamente

" -ForegroundColor Cyan

Write-Host "🎯 RESULTADO FINAL" -ForegroundColor Green
Write-Host @"
✅ Nenhuma tarefa será criada/editada SEM título
✅ Validação em 3 níveis (Frontend + Backend + BD)
✅ Mensagens claras em português
✅ Dados históricos preservados como "Sem Titulo - Corrigir"

🚀 SISTEMA PRONTO PARA USAR!

" -ForegroundColor Green

Write-Host "📞 EM CASO DE PROBLEMAS" -ForegroundColor Yellow
Write-Host @"
Erro na Migration:
  php artisan migrate:rollback
  php artisan migrate

Limpar Cache (se necessário):
  php artisan cache:clear

Verificar Status das Migrations:
  php artisan migrate:status

Reverter (se precisar desfazer):
  php artisan migrate:rollback

" -ForegroundColor Red

Write-Host "═════════════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host "Implementação Concluída - 2 de Março de 2026" -ForegroundColor Cyan
Write-Host "═════════════════════════════════════════════════════════════════" -ForegroundColor Cyan

