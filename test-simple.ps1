# Teste Simples de Token
Write-Host "1. Obtendo token..." -ForegroundColor Cyan

$tokenBody = "client_id=task-app&client_secret=XRugFZF5nv06ME45GvakdCs4l7Yrh7V5&grant_type=password&username=admin&password=admin123"

try {
    $tokenResp = Invoke-WebRequest -Uri "http://localhost:8080/realms/task-controller/protocol/openid-connect/token" `
        -Method POST `
        -ContentType "application/x-www-form-urlencoded" `
        -Body $tokenBody `
        -UseBasicParsing

    $tokenJson = $tokenResp.Content | ConvertFrom-Json
    $token = $tokenJson.access_token

    Write-Host "   ✓ Token obtido!" -ForegroundColor Green
    Write-Host "   Token (primeiros 50 chars): $($token.Substring(0,50))..." -ForegroundColor Gray
} catch {
    Write-Host "   ✗ Erro: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "2. Testando endpoint de API..." -ForegroundColor Cyan

$headers = @{
    "Authorization" = "Bearer $token"
    "Content-Type" = "application/json"
    "Accept" = "application/json"
}

$body = '{"description":"Teste de API","status":"Em Planejamento"}'

Write-Host "   Enviando: $body" -ForegroundColor Gray

try {
    $resp = Invoke-WebRequest -Uri "http://localhost:8000/api/tasks" `
        -Method POST `
        -Headers $headers `
        -Body $body `
        -UseBasicParsing

    Write-Host "   ✓ Status: $($resp.StatusCode)" -ForegroundColor Green
    Write-Host "   Resposta:" -ForegroundColor Cyan
    Write-Host "   $($resp.Content)" -ForegroundColor White

} catch {
    $statusCode = $_.Exception.Response.StatusCode.Value__
    Write-Host "   ✗ Status: $statusCode" -ForegroundColor Red

    try {
        $stream = $_.Exception.Response.GetResponseStream()
        $reader = New-Object System.IO.StreamReader($stream)
        $responseBody = $reader.ReadToEnd()
        Write-Host "   Resposta do servidor:" -ForegroundColor Yellow
        Write-Host "   $responseBody" -ForegroundColor Gray
    } catch {
        Write-Host "   Mensagem: $($_.Exception.Message)" -ForegroundColor Red
    }
}

