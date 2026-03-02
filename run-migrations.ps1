# Script para executar migrations
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "Executando Migrations - Criação da Tabela Tasks" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "1. Verificando containers..." -ForegroundColor Yellow
docker ps --filter "name=task_app" --format "table {{.Names}}\t{{.Status}}"
Write-Host ""

Write-Host "2. Executando migrations..." -ForegroundColor Yellow
docker exec task_app php artisan migrate --force
Write-Host ""

Write-Host "3. Verificando status das migrations..." -ForegroundColor Yellow
docker exec task_app php artisan migrate:status
Write-Host ""

Write-Host "✅ Pronto! A tabela 'tasks' deve ter sido criada." -ForegroundColor Green
Write-Host ""
Write-Host "Para verificar, você pode executar:" -ForegroundColor Yellow
Write-Host "   docker exec task_mysql mysql -u laravel -psecret taskcontroller -e 'SHOW TABLES;'" -ForegroundColor Gray

