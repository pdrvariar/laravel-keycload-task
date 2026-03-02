# Script PowerShell para aplicar todas as correções
# Executar: .\apply-fixes.ps1

Write-Host "🚀 Aplicando todas as correções..." -ForegroundColor Green
Write-Host ""

# 1. Parar containers
Write-Host "1️⃣  Parando containers..." -ForegroundColor Cyan
docker-compose down -v

Write-Host ""
Write-Host "2️⃣  Aguardando 5 segundos..." -ForegroundColor Cyan
Start-Sleep -Seconds 5

# 3. Iniciar containers
Write-Host "3️⃣  Iniciando containers com novas configurações..." -ForegroundColor Cyan
docker-compose up -d

Write-Host ""
Write-Host "4️⃣  Aguardando Keycloak inicializar (2-3 minutos)..." -ForegroundColor Cyan
Write-Host "   Você pode monitorar com: docker-compose logs -f keycloak" -ForegroundColor Yellow
Start-Sleep -Seconds 30

Write-Host ""
Write-Host "✅ Todos os containers iniciados!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Próximos passos:" -ForegroundColor Green
Write-Host "   1. Aguardar 2-3 minutos para Keycloak ficar pronto" -ForegroundColor White
Write-Host "   2. Abrir: http://localhost:8000" -ForegroundColor White
Write-Host "   3. Fazer login" -ForegroundColor White
Write-Host "   4. Testar criar tarefa" -ForegroundColor White
Write-Host "   5. Verificar se token dura 2 horas" -ForegroundColor White
Write-Host ""
Write-Host "📖 Para mais detalhes, veja: CHECKLIST_IMPLEMENTACAO.md" -ForegroundColor Cyan
Write-Host ""

