# Script para testar criação de usuário e tarefa
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "Teste Completo: Usuário + Tarefa" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Passo 1: Obter Token
Write-Host "1. Obtendo token de acesso do Keycloak..." -ForegroundColor Yellow
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
    Write-Host "   Token (primeiros 50 chars): $($token.Substring(0,50))..." -ForegroundColor Gray
} catch {
    Write-Host "   ❌ Erro ao obter token:" -ForegroundColor Red
    Write-Host "   $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

Write-Host ""

# Passo 2: Criar Tarefa (isso deve criar o usuário automaticamente se não existir)
Write-Host "2. Criando tarefa via API..." -ForegroundColor Yellow
Write-Host "   (O usuário será criado automaticamente no banco se não existir)" -ForegroundColor Gray
try {
    $headers = @{
        'Authorization' = "Bearer $token"
        'Content-Type' = 'application/json'
        'Accept' = 'application/json'
    }

    $taskData = @{
        description = "Teste Completo - $(Get-Date -Format 'HH:mm:ss') - Usuário + Tarefa"
        status = 'Em Planejamento'
    } | ConvertTo-Json

    $response = Invoke-RestMethod -Uri 'http://localhost:8000/api/tasks' -Method Post -Headers $headers -Body $taskData -ErrorAction Stop

    Write-Host "   ✅ Tarefa criada com sucesso!" -ForegroundColor Green
    Write-Host "   ID da Tarefa: $($response.data.id)" -ForegroundColor Green
    Write-Host "   User ID: $($response.data.user_id)" -ForegroundColor Green
    Write-Host "   Descrição: $($response.data.description)" -ForegroundColor Gray
    Write-Host "   Status: $($response.data.status)" -ForegroundColor Gray
} catch {
    Write-Host "   ❌ Erro ao criar tarefa:" -ForegroundColor Red
    if ($_.ErrorDetails.Message) {
        $errorObj = $_.ErrorDetails.Message | ConvertFrom-Json
        Write-Host "   Mensagem: $($errorObj.message)" -ForegroundColor Red
    } else {
        Write-Host "   $($_.Exception.Message)" -ForegroundColor Red
    }
    exit 1
}

Write-Host ""

# Passo 3: Verificar usuário no banco
Write-Host "3. Verificando usuário criado no banco de dados..." -ForegroundColor Yellow
docker exec task_mysql mysql -u laravel -psecret taskcontroller -e "SELECT id, name, email, created_at FROM users;"

Write-Host ""

# Passo 4: Verificar tarefas no banco
Write-Host "4. Verificando tarefas criadas no banco de dados..." -ForegroundColor Yellow
docker exec task_mysql mysql -u laravel -psecret taskcontroller -e "SELECT id, user_id, description, status, created_at FROM tasks ORDER BY id DESC LIMIT 5;"

Write-Host ""
Write-Host "✅ Teste completo finalizado!" -ForegroundColor Green

