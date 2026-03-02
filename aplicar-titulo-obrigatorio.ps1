#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Script para aplicar validação obrigatória de Título em tarefas
.DESCRIPTION
    Executa as migrations necessárias para tornar o campo título obrigatório
#>

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "Validação Obrigatória de Título" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

# Cores
$success = @{ ForegroundColor = "Green" }
$warning = @{ ForegroundColor = "Yellow" }
$error = @{ ForegroundColor = "Red" }
$info = @{ ForegroundColor = "Cyan" }

$laravelPath = "C:\MyDev\Projetos\task-controller\laravel"

# Verificar se o diretório existe
if (-not (Test-Path $laravelPath)) {
    Write-Host "❌ Diretório Laravel não encontrado em: $laravelPath" @error
    exit 1
}

Set-Location $laravelPath

Write-Host "📂 Diretório: $(Get-Location)" @info

# Verificar se artisan existe
if (-not (Test-Path "artisan")) {
    Write-Host "❌ Arquivo artisan não encontrado" @error
    exit 1
}

Write-Host "`n📋 Etapas da Implementação:`n" @info

# 1. Backup do banco de dados
Write-Host "1️⃣ Criando backup do banco de dados (se necessário)..." @warning
$dbFile = "database\database.sqlite"
if (Test-Path $dbFile) {
    $backupFile = "$dbFile.backup_$(Get-Date -Format 'yyyyMMdd_HHmmss')"
    Copy-Item $dbFile $backupFile
    Write-Host "   ✅ Backup criado: $backupFile" @success
} else {
    Write-Host "   ⚠️  Arquivo de banco de dados não encontrado" @warning
}

# 2. Executar migrations
Write-Host "`n2️⃣ Executando migrations..." @info
Write-Host "   Executando: php artisan migrate" @warning

php artisan migrate

if ($LASTEXITCODE -eq 0) {
    Write-Host "   ✅ Migrations executadas com sucesso!" @success
} else {
    Write-Host "   ❌ Erro ao executar migrations" @error
    exit 1
}

# 3. Verificar status do banco
Write-Host "`n3️⃣ Verificando integridade do banco de dados..." @info
Write-Host "   Executando: php artisan migrate:status" @warning

php artisan migrate:status

# 4. Verificar tarefas corrigidas
Write-Host "`n4️⃣ Verificando tarefas que foram corrigidas..." @info
Write-Host "   Executando: php artisan tinker" @warning

Write-Host "
Digite os seguintes comandos no Tinker:

1. Contar tarefas corrigidas:
   >>> DB::table('tasks')->where('title', 'Sem Titulo - Corrigir')->count()

2. Listar tarefas corrigidas:
   >>> DB::table('tasks')->where('title', 'Sem Titulo - Corrigir')->get()

3. Sair do Tinker:
   >>> exit()

" @info

php artisan tinker

# 5. Resumo
Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "Resumo da Implementação" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

Write-Host "✅ Mudanças Aplicadas:" @success
Write-Host "
1. Campo 'title' agora é obrigatório (NOT NULL)
2. Tarefas sem título foram corrigidas para 'Sem Titulo - Corrigir'
3. Validação implementada em:
   - API (TaskController)
   - Formulário de Criação
   - Formulário de Edição
   - Modal de Edição (Admin)
   - Modal de Clonagem (Admin)
"

Write-Host "`n📚 Documentação:" @info
Write-Host "   Veja: IMPLEMENTACAO_TITULO_OBRIGATORIO.md`n"

Write-Host "🧪 Próximas Etapas:" @warning
Write-Host "
1. Teste de Criação:
   - Tente criar tarefa sem título (deve mostrar erro)
   - Crie tarefa com título válido (deve funcionar)

2. Teste de Edição:
   - Tente salvar edição sem título (deve mostrar erro)
   - Edite título válido (deve funcionar)

3. Teste de Clonagem (Admin):
   - Clone uma tarefa com novo título
   - Verifique se foi criada corretamente

4. Teste de API:
   - POST /api/tasks sem título (deve retornar 422)
   - PUT /api/tasks/{id} sem título (deve retornar 422)
"

Write-Host "`n========================================`n" -ForegroundColor Cyan

