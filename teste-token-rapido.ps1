# Teste Rápido: Token Keycloak
# Executa automaticamente com administrador@example.com

Write-Host "🔍 Testando token para administrador@example.com..." -ForegroundColor Cyan
Write-Host ""

$body = @{
    grant_type = "password"
    client_id = "task-controller"
    client_secret = "your-client-secret"
    username = "administrador@example.com"
    password = "admin123"
    scope = "openid profile email"
}

try {
    $response = Invoke-RestMethod `
        -Uri "http://localhost:8080/realms/task-controller/protocol/openid-connect/token" `
        -Method Post `
        -Body $body `
        -ContentType "application/x-www-form-urlencoded"

    Write-Host "✅ Token obtido!" -ForegroundColor Green
    Write-Host ""

    # Decodificar Access Token
    $accessToken = $response.access_token
    $parts = $accessToken.Split('.')

    # Corrigir padding
    $base64 = $parts[1].Replace('-', '+').Replace('_', '/')
    switch ($base64.Length % 4) {
        2 { $base64 += "==" }
        3 { $base64 += "=" }
    }

    $json = [System.Text.Encoding]::UTF8.GetString([System.Convert]::FromBase64String($base64))
    $payload = $json | ConvertFrom-Json

    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host "  ANÁLISE DO ACCESS TOKEN              " -ForegroundColor Cyan
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host ""

    # Mostrar resource_access
    Write-Host "resource_access:" -ForegroundColor Yellow
    Write-Host ($payload.resource_access | ConvertTo-Json -Depth 5) -ForegroundColor Gray
    Write-Host ""

    # Verificar task-controller
    if ($payload.resource_access.'task-controller') {
        Write-Host "✅ task-controller encontrado!" -ForegroundColor Green

        $roles = $payload.resource_access.'task-controller'.roles
        if ($roles) {
            Write-Host "✅ Roles: $($roles -join ', ')" -ForegroundColor Green

            foreach ($role in $roles) {
                if ($role -eq "admin") {
                    Write-Host ""
                    Write-Host "🎯 Badge esperado: 🛡️  ADMINISTRADOR (laranja)" -ForegroundColor DarkYellow
                }
            }
        } else {
            Write-Host "❌ Nenhuma role encontrada!" -ForegroundColor Red
        }
    } else {
        Write-Host "❌ task-controller NÃO encontrado!" -ForegroundColor Red
        Write-Host ""
        Write-Host "Clientes disponíveis:" -ForegroundColor Yellow
        $payload.resource_access.PSObject.Properties.Name | ForEach-Object {
            Write-Host "  • $_" -ForegroundColor Gray
        }
    }

    Write-Host ""
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host "  PAYLOAD COMPLETO                     " -ForegroundColor Cyan
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host ($payload | ConvertTo-Json -Depth 10) -ForegroundColor DarkGray

} catch {
    Write-Host "❌ ERRO: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host ""
    Write-Host "Verifique se:" -ForegroundColor Yellow
    Write-Host "  • Keycloak está rodando" -ForegroundColor Cyan
    Write-Host "  • Senha está correta (admin123)" -ForegroundColor Cyan
    Write-Host "  • Client secret está configurado" -ForegroundColor Cyan
}

Write-Host ""

