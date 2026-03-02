# TESTE DETALHADO - API de Tarefas
Write-Host "Obtendo token..." -ForegroundColor Yellow

$body = @{
    client_id = 'task-app'
    client_secret = 'XRugFZF5nv06ME45GvakdCs4l7Yrh7V5'
    grant_type = 'password'
    username = 'admin'
    password = 'admin123'
}

try {
    $tokenResponse = Invoke-RestMethod -Uri 'http://localhost:8080/realms/task-controller/protocol/openid-connect/token' -Method Post -Body $body
    $token = $tokenResponse.access_token
    Write-Host "✓ Token obtido" -ForegroundColor Green
} catch {
    Write-Host "✗ Erro ao obter token: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "Criando tarefa..." -ForegroundColor Yellow

$headers = @{
    'Authorization' = "Bearer $token"
    'Content-Type' = 'application/json'
    'Accept' = 'application/json'
}

$taskData = @{
    description = "Teste de tarefa - $(Get-Date -Format 'HH:mm:ss')"
    status = 'Em Planejamento'
} | ConvertTo-Json

Write-Host "Dados enviados:" -ForegroundColor Cyan
Write-Host $taskData -ForegroundColor Gray
Write-Host ""

try {
    $response = Invoke-WebRequest -Uri 'http://localhost:8000/api/tasks' -Method Post -Headers $headers -Body $taskData -UseBasicParsing
    $task = $response.Content | ConvertFrom-Json

    Write-Host "✅ SUCESSO!" -ForegroundColor Green
    Write-Host ($task | ConvertTo-Json -Depth 5) -ForegroundColor White

} catch {
    Write-Host "✗ ERRO!" -ForegroundColor Red
    Write-Host "Status: $($_.Exception.Response.StatusCode.Value__)" -ForegroundColor Red

    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Resposta completa:" -ForegroundColor Yellow
        Write-Host $responseBody -ForegroundColor Gray
    } else {
        Write-Host "Mensagem: $($_.Exception.Message)" -ForegroundColor Red
    }
}

