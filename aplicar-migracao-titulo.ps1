#!/usr/bin/env pwsh
# Script para aplicar a migração do campo título

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host " APLICANDO MIGRAÇÃO - CAMPO TÍTULO" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

# Passo 1: Iniciar containers
Write-Host "[PASSO 1] Iniciando containers Docker..." -ForegroundColor Yellow
Write-Host "Comando: docker-compose up -d`n" -ForegroundColor Gray

Set-Location "c:\MyDev\Projetos\task-controller"
docker-compose up -d

Write-Host "`n[PASSO 2] Aguardando containers iniciarem..." -ForegroundColor Yellow
Write-Host "Isso pode levar até 60 segundos...`n" -ForegroundColor Gray
Start-Sleep -Seconds 30

# Passo 2: Verificar containers
Write-Host "[PASSO 3] Verificando status dos containers..." -ForegroundColor Yellow
Write-Host "Comando: docker ps`n" -ForegroundColor Gray
docker ps | Where-Object { $_.Names -like "task_*" }

# Passo 3: Executar migration
Write-Host "`n[PASSO 4] Executando migration..." -ForegroundColor Yellow
Write-Host "Comando: docker exec task_app php artisan migrate`n" -ForegroundColor Gray

try {
    docker exec task_app php artisan migrate
    Write-Host "`n✓ Migration executada com sucesso!" -ForegroundColor Green
} catch {
    Write-Host "`n✗ Erro ao executar migration!" -ForegroundColor Red
    Write-Host "Detalhes: $_`n" -ForegroundColor Red
    exit 1
}

# Passo 4: Verificar status
Write-Host "`n[PASSO 5] Verificando status das migrations..." -ForegroundColor Yellow
Write-Host "Comando: docker exec task_app php artisan migrate:status`n" -ForegroundColor Gray

docker exec task_app php artisan migrate:status

# Sucesso
Write-Host "`n========================================" -ForegroundColor Green
Write-Host " MIGRAÇÃO CONCLUÍDA COM SUCESSO!" -ForegroundColor Green
Write-Host "========================================`n" -ForegroundColor Green

Write-Host "✓ Campo 'title' adicionado à tabela tasks" -ForegroundColor Green
Write-Host "✓ Tarefas existentes: título = (SEM TITULO)`n" -ForegroundColor Green

Write-Host "Próximos passos:" -ForegroundColor Cyan
Write-Host "1. Acesse http://localhost:8000" -ForegroundColor White
Write-Host "2. Faça login com suas credenciais"
Write-Host "3. Vá para 'Minhas Tarefas'"
Write-Host "4. Crie ou edite uma tarefa e use o novo campo de título`n"

Write-Host "Documentação:" -ForegroundColor Cyan
Write-Host "- IMPLEMENTACAO_TITULO_TAREFAS.md" -ForegroundColor Yellow
Write-Host "- GUIA_MIGRACAO_TITULO.md`n" -ForegroundColor Yellow

Read-Host "Pressione ENTER para sair"

