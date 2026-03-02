# Script para testar as correções de carregamento de tarefas
# Uso: .\test-tasks-loading.ps1

Write-Host "🧪 Teste de Carregamento de Tarefas" -ForegroundColor Cyan
Write-Host "===================================" -ForegroundColor Cyan
Write-Host ""

# Config
$API_URL = "http://localhost:8000/api"
$LOGIN_URL = "http://localhost:8000/auth/login"

Write-Host "📝 Step 1: Fazer login e obter token" -ForegroundColor Yellow
Write-Host "------------------------------------" -ForegroundColor Yellow

# Credenciais de teste (ajuste conforme necessário)
$EMAIL = "teste@example.com"
$PASSWORD = "password123"

# Login
try {
    $loginBody = @{
        email = $EMAIL
        password = $PASSWORD
    } | ConvertTo-Json

    $loginResponse = Invoke-WebRequest -Uri $LOGIN_URL `
        -Method Post `
        -Headers @{"Content-Type" = "application/json"} `
        -Body $loginBody `
        -UseBasicParsing

    $loginData = $loginResponse.Content | ConvertFrom-Json
    $TOKEN = $loginData.token

    if ($null -eq $TOKEN) {
        Write-Host "❌ Falha ao obter token" -ForegroundColor Red
        Write-Host "Resposta: $($loginResponse.Content)" -ForegroundColor Red
        exit 1
    }

    Write-Host "✅ Token obtido com sucesso" -ForegroundColor Green
    Write-Host "Token: $($TOKEN.Substring(0, 20))..." -ForegroundColor Green
} catch {
    Write-Host "❌ Erro ao fazer login: $_" -ForegroundColor Red
    exit 1
}

Write-Host ""

Write-Host "🔍 Step 2: Testar GET /api/tasks" -ForegroundColor Yellow
Write-Host "--------------------------------" -ForegroundColor Yellow

try {
    $tasksResponse = Invoke-WebRequest -Uri "$API_URL/tasks" `
        -Method Get `
        -Headers @{
            "Authorization" = "Bearer $TOKEN"
            "Accept" = "application/json"
        } `
        -UseBasicParsing

    $tasksData = $tasksResponse.Content | ConvertFrom-Json

    if ($tasksData.success) {
        Write-Host "✅ GET /api/tasks funcionando" -ForegroundColor Green
        Write-Host "Total de tarefas: $($tasksData.data.Count)" -ForegroundColor Green
    } else {
        Write-Host "❌ GET /api/tasks falhou" -ForegroundColor Red
        Write-Host "Resposta: $($tasksResponse.Content)" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ GET /api/tasks falhou: $_" -ForegroundColor Red
}

Write-Host ""

Write-Host "📝 Step 3: Testar POST /api/tasks (criar tarefa)" -ForegroundColor Yellow
Write-Host "----------------------------------------------" -ForegroundColor Yellow

try {
    $timestamp = Get-Date -Format "yyyyMMddHHmmss"
    $createBody = @{
        description = "Tarefa de teste - $timestamp"
        status = "Em Planejamento"
    } | ConvertTo-Json

    $createResponse = Invoke-WebRequest -Uri "$API_URL/tasks" `
        -Method Post `
        -Headers @{
            "Authorization" = "Bearer $TOKEN"
            "Content-Type" = "application/json"
            "Accept" = "application/json"
        } `
        -Body $createBody `
        -UseBasicParsing

    $createData = $createResponse.Content | ConvertFrom-Json

    if ($createData.success) {
        Write-Host "✅ POST /api/tasks funcionando" -ForegroundColor Green
        $TASK_ID = $createData.data.id
        Write-Host "Task ID: $TASK_ID" -ForegroundColor Green
    } else {
        Write-Host "❌ POST /api/tasks falhou" -ForegroundColor Red
        Write-Host "Resposta: $($createResponse.Content)" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ POST /api/tasks falhou: $_" -ForegroundColor Red
}

Write-Host ""

Write-Host "📋 Step 4: Testar GET /api/tasks/{id}" -ForegroundColor Yellow
Write-Host "-----------------------------------" -ForegroundColor Yellow

if ($null -ne $TASK_ID) {
    try {
        $getTaskResponse = Invoke-WebRequest -Uri "$API_URL/tasks/$TASK_ID" `
            -Method Get `
            -Headers @{
                "Authorization" = "Bearer $TOKEN"
                "Accept" = "application/json"
            } `
            -UseBasicParsing

        $getTaskData = $getTaskResponse.Content | ConvertFrom-Json

        if ($getTaskData.success) {
            Write-Host "✅ GET /api/tasks/{id} funcionando" -ForegroundColor Green
            Write-Host "Descrição: $($getTaskData.data.description)" -ForegroundColor Green
        } else {
            Write-Host "❌ GET /api/tasks/{id} falhou" -ForegroundColor Red
        }
    } catch {
        Write-Host "❌ GET /api/tasks/{id} falhou: $_" -ForegroundColor Red
    }
} else {
    Write-Host "⚠️  Pulando (nenhuma tarefa foi criada)" -ForegroundColor Yellow
}

Write-Host ""

Write-Host "✏️  Step 5: Testar PUT /api/tasks/{id}" -ForegroundColor Yellow
Write-Host "------------------------------------" -ForegroundColor Yellow

if ($null -ne $TASK_ID) {
    try {
        $timestamp = Get-Date -Format "yyyyMMddHHmmss"
        $updateBody = @{
            description = "Tarefa atualizada - $timestamp"
            status = "Em Andamento"
        } | ConvertTo-Json

        $updateResponse = Invoke-WebRequest -Uri "$API_URL/tasks/$TASK_ID" `
            -Method Put `
            -Headers @{
                "Authorization" = "Bearer $TOKEN"
                "Content-Type" = "application/json"
                "Accept" = "application/json"
            } `
            -Body $updateBody `
            -UseBasicParsing

        $updateData = $updateResponse.Content | ConvertFrom-Json

        if ($updateData.success) {
            Write-Host "✅ PUT /api/tasks/{id} funcionando" -ForegroundColor Green
            Write-Host "Status: $($updateData.data.status)" -ForegroundColor Green
        } else {
            Write-Host "❌ PUT /api/tasks/{id} falhou" -ForegroundColor Red
        }
    } catch {
        Write-Host "❌ PUT /api/tasks/{id} falhou: $_" -ForegroundColor Red
    }
} else {
    Write-Host "⚠️  Pulando (nenhuma tarefa foi criada)" -ForegroundColor Yellow
}

Write-Host ""

Write-Host "🗑️  Step 6: Testar DELETE /api/tasks/{id}" -ForegroundColor Yellow
Write-Host "--------------------------------------" -ForegroundColor Yellow

if ($null -ne $TASK_ID) {
    try {
        $deleteResponse = Invoke-WebRequest -Uri "$API_URL/tasks/$TASK_ID" `
            -Method Delete `
            -Headers @{
                "Authorization" = "Bearer $TOKEN"
                "Accept" = "application/json"
            } `
            -UseBasicParsing

        $deleteData = $deleteResponse.Content | ConvertFrom-Json

        if ($deleteData.success) {
            Write-Host "✅ DELETE /api/tasks/{id} funcionando" -ForegroundColor Green
        } else {
            Write-Host "❌ DELETE /api/tasks/{id} falhou" -ForegroundColor Red
        }
    } catch {
        Write-Host "❌ DELETE /api/tasks/{id} falhou: $_" -ForegroundColor Red
    }
} else {
    Write-Host "⚠️  Pulando (nenhuma tarefa foi criada)" -ForegroundColor Yellow
}

Write-Host ""

Write-Host "===================================" -ForegroundColor Cyan
Write-Host "✅ Testes completados!" -ForegroundColor Cyan
Write-Host ""
Write-Host "Se todos os testes passaram:" -ForegroundColor Cyan
Write-Host "- Dashboard deve carregar tarefas corretamente" -ForegroundColor Cyan
Write-Host "- Criar, editar e deletar tarefas devem funcionar" -ForegroundColor Cyan
Write-Host "- Filtros e busca devem trabalhar" -ForegroundColor Cyan
Write-Host ""


