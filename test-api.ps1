# TESTE RÁPIDO - API de Tarefas
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "Teste Rápido: Criação de Tarefa via API" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Passo 1: Obter Token
Write-Host "1. Obtendo token de acesso..." -ForegroundColor Yellow
try {
    $body = @{
        client_id = 'task-app'
        client_secret = 'XRugFZF5nv06ME45GvakdCs4l7Yrh7V5'
        grant_type = 'password'
        username = 'admin'
        password = 'admin123'
    }

    $tokenResponse = Invoke-RestMethod -Uri 'http://localhost:8080/realms/task-controller/protocol/openid-connect/token' -Method Post -Body $body -ErrorAction Stop
    $token = $tokenResponse.access_token

    Write-Host "   ✅ Token obtido com sucesso!" -ForegroundColor Green
    Write-Host "   Token: $($token.Substring(0,50))..." -ForegroundColor Gray
} catch {
    Write-Host "   ❌ Erro ao obter token:" -ForegroundColor Red
    Write-Host "   $($_.Exception.Message)" -ForegroundColor Red
    Write-Host ""
    Write-Host "Verifique se o Keycloak está rodando:" -ForegroundColor Yellow
    Write-Host "   docker compose ps keycloak" -ForegroundColor Gray
    exit 1
}

Write-Host ""

# Passo 2: Criar Tarefa
Write-Host "2. Criando tarefa via API..." -ForegroundColor Yellow
try {
    $headers = @{
        'Authorization' = "Bearer $token"
        'Content-Type' = 'application/json'
        'Accept' = 'application/json'
    }

    $taskData = @{
        description = "Tarefa de Teste - $(Get-Date -Format 'HH:mm:ss') - Esta tarefa foi criada para testar a correção da chave pública do Keycloak"
        status = 'Em Planejamento'
    } | ConvertTo-Json

    $task = Invoke-RestMethod -Uri 'http://localhost:8000/api/tasks' -Method Post -Headers $headers -Body $taskData -ErrorAction Stop

    Write-Host "   ✅ Tarefa criada com sucesso!" -ForegroundColor Green
    Write-Host ""
    Write-Host "   Detalhes da Tarefa:" -ForegroundColor Cyan
    Write-Host "   ==================" -ForegroundColor Cyan
    Write-Host "   ID:          $($task.id)" -ForegroundColor White
    Write-Host "   Descrição:   $($task.description)" -ForegroundColor White
    Write-Host "   Status:      $($task.status)" -ForegroundColor White
    Write-Host "   Criado em:   $($task.created_at)" -ForegroundColor White

} catch {
    Write-Host "   ❌ Erro ao criar tarefa:" -ForegroundColor Red
    Write-Host "   $($_.Exception.Message)" -ForegroundColor Red

    if ($_.Exception.Message -match "publicKey") {
        Write-Host ""
        Write-Host "   O erro da chave pública ainda persiste!" -ForegroundColor Yellow
        Write-Host "   Tente:" -ForegroundColor Yellow
        Write-Host "   1. docker compose restart app" -ForegroundColor Gray
        Write-Host "   2. docker compose exec app php artisan config:clear" -ForegroundColor Gray
        Write-Host "   3. docker compose exec app php artisan config:cache" -ForegroundColor Gray
    }
    exit 1
}

Write-Host ""

# Passo 3: Listar Tarefas
Write-Host "3. Listando todas as tarefas..." -ForegroundColor Yellow
try {
    $tasks = Invoke-RestMethod -Uri 'http://localhost:8000/api/tasks' -Method Get -Headers $headers -ErrorAction Stop

    Write-Host "   ✅ Total de tarefas: $($tasks.Count)" -ForegroundColor Green
    Write-Host ""

    if ($tasks.Count -gt 0) {
        Write-Host "   Últimas 5 tarefas:" -ForegroundColor Cyan
        $tasks | Select-Object -First 5 | ForEach-Object {
            $desc = if ($_.description.Length -gt 50) { $_.description.Substring(0, 50) + "..." } else { $_.description }
            Write-Host "   - [$($_.id)] $desc [$($_.status)]" -ForegroundColor White
        }
    }

} catch {
    Write-Host "   ❌ Erro ao listar tarefas:" -ForegroundColor Red
    Write-Host "   $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "✅ TESTE CONCLUÍDO COM SUCESSO!" -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "A correção da chave pública está funcionando!" -ForegroundColor Green
Write-Host "Agora você pode criar tarefas normalmente via API." -ForegroundColor Green
Write-Host ""

