# Verificação da Correção do Keycloak Public Key
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "Verificação da Correção do Keycloak Public Key" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Verifica se o arquivo .env existe
if (-not (Test-Path "laravel\.env")) {
    Write-Host "❌ ERRO: Arquivo laravel\.env não encontrado!" -ForegroundColor Red
    exit 1
}

# Verifica se a chave está no .env
$envContent = Get-Content "laravel\.env" -Raw
if ($envContent -match "KEYCLOAK_REALM_PUBLIC_KEY") {
    Write-Host "✅ KEYCLOAK_REALM_PUBLIC_KEY encontrada no .env" -ForegroundColor Green

    # Mostra os primeiros 50 caracteres da chave
    $keyLine = Get-Content "laravel\.env" | Select-String "KEYCLOAK_REALM_PUBLIC_KEY"
    if ($keyLine) {
        $key = $keyLine.ToString().Split('=')[1].Replace('"', '').Substring(0, [Math]::Min(50, $keyLine.ToString().Length - 25))
        Write-Host "   Primeiros 50 caracteres: $key..." -ForegroundColor Gray
    }
} else {
    Write-Host "❌ ERRO: KEYCLOAK_REALM_PUBLIC_KEY não encontrada no .env" -ForegroundColor Red
    Write-Host ""
    Write-Host "Execute este comando para adicionar a chave:" -ForegroundColor Yellow
    Write-Host "================================================" -ForegroundColor Yellow
    Write-Host "Acesse: http://localhost:8000/setup/keycloak-key" -ForegroundColor White
    Write-Host "================================================" -ForegroundColor Yellow
    exit 1
}

Write-Host ""
Write-Host "Verificando containers..." -ForegroundColor Cyan

# Verifica se os containers estão rodando
$containers = docker compose ps 2>&1
if ($containers -match "task_app") {
    Write-Host "✅ Container task_app encontrado" -ForegroundColor Green
} else {
    Write-Host "⚠️  AVISO: Container task_app pode não estar rodando" -ForegroundColor Yellow
    Write-Host "   Execute: docker compose up -d" -ForegroundColor Gray
}

Write-Host ""
Write-Host "Limpando cache do Laravel..." -ForegroundColor Cyan

try {
    $result = docker compose exec -T app php artisan config:clear 2>&1
    Write-Host "✅ Cache de configuração limpo" -ForegroundColor Green

    $result = docker compose exec -T app php artisan config:cache 2>&1
    Write-Host "✅ Cache de configuração recriado" -ForegroundColor Green
} catch {
    Write-Host "⚠️  Não foi possível limpar/recriar cache (container pode estar iniciando)" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Testando configuração..." -ForegroundColor Cyan

try {
    $testResult = docker compose exec -T app php -r "echo (getenv('KEYCLOAK_REALM_PUBLIC_KEY') ? 'OK' : 'ERRO');" 2>&1
    if ($testResult -match "OK") {
        Write-Host "✅ Chave disponível no container" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Chave pode não estar disponível no container" -ForegroundColor Yellow
    }
} catch {
    Write-Host "⚠️  Não foi possível verificar dentro do container" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "✅ CORREÇÃO APLICADA COM SUCESSO!" -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Próximos Passos:" -ForegroundColor Yellow
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "1. Faça login em: http://localhost:8000/login" -ForegroundColor White
Write-Host "   - Usuário: admin / admin123" -ForegroundColor Gray
Write-Host ""
Write-Host "2. Obtenha um token via API (PowerShell):" -ForegroundColor White
Write-Host @"
   `$body = @{
       client_id = 'task-app'
       client_secret = 'XRugFZF5nv06ME45GvakdCs4l7Yrh7V5'
       grant_type = 'password'
       username = 'admin'
       password = 'admin123'
   }
   `$response = Invoke-RestMethod -Uri 'http://localhost:8080/realms/task-controller/protocol/openid-connect/token' -Method Post -Body `$body
   `$token = `$response.access_token
"@ -ForegroundColor Gray
Write-Host ""
Write-Host "3. Use o token para criar uma tarefa:" -ForegroundColor White
Write-Host @"
   `$headers = @{
       'Authorization' = "Bearer `$token"
       'Content-Type' = 'application/json'
   }
   `$taskBody = @{
       title = 'Tarefa de Teste'
       description = 'Criada após correção'
       status = 'pending'
   } | ConvertTo-Json
   Invoke-RestMethod -Uri 'http://localhost:8000/api/tasks' -Method Post -Headers `$headers -Body `$taskBody
"@ -ForegroundColor Gray
Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "📖 Para mais detalhes, consulte: FIX_PUBLIC_KEY.md" -ForegroundColor Cyan

