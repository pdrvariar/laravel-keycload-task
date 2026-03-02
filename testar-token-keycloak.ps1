# Script de Teste: Verificar Token do Keycloak
# Este script testa diretamente a autenticação no Keycloak

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  TESTE DE TOKEN KEYCLOAK              " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

$keycloakUrl = "http://localhost:8080"
$realm = "task-controller"
$clientId = "task-controller"
$clientSecret = "your-client-secret-here"  # AJUSTE SE NECESSÁRIO

Write-Host "Configuração:" -ForegroundColor Yellow
Write-Host "  Keycloak URL: $keycloakUrl" -ForegroundColor Gray
Write-Host "  Realm: $realm" -ForegroundColor Gray
Write-Host "  Client ID: $clientId" -ForegroundColor Gray
Write-Host ""

# Solicitar credenciais
Write-Host "Digite o email do usuário: " -ForegroundColor Cyan -NoNewline
$email = Read-Host

Write-Host "Digite a senha: " -ForegroundColor Cyan -NoNewline
$password = Read-Host -AsSecureString

# Converter senha segura para texto
$BSTR = [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($password)
$plainPassword = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)

Write-Host ""
Write-Host "🔄 Obtendo token do Keycloak..." -ForegroundColor Yellow
Write-Host ""

try {
    # Preparar body
    $body = @{
        grant_type = "password"
        client_id = $clientId
        client_secret = $clientSecret
        username = $email
        password = $plainPassword
        scope = "openid profile email"
    }

    # Fazer request
    $response = Invoke-RestMethod `
        -Uri "$keycloakUrl/realms/$realm/protocol/openid-connect/token" `
        -Method Post `
        -Body $body `
        -ContentType "application/x-www-form-urlencoded"

    Write-Host "✅ Token obtido com sucesso!" -ForegroundColor Green
    Write-Host ""

    # Decodificar ID Token
    $idToken = $response.id_token
    $idParts = $idToken.Split('.')
    $idPayloadJson = [System.Text.Encoding]::UTF8.GetString([System.Convert]::FromBase64String($idParts[1]))
    $idPayload = $idPayloadJson | ConvertFrom-Json

    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host "  ID TOKEN (informações do usuário)    " -ForegroundColor Cyan
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Nome: " -NoNewline -ForegroundColor Yellow
    Write-Host $idPayload.name -ForegroundColor White
    Write-Host "Email: " -NoNewline -ForegroundColor Yellow
    Write-Host $idPayload.email -ForegroundColor White
    Write-Host "Preferred Username: " -NoNewline -ForegroundColor Yellow
    Write-Host $idPayload.preferred_username -ForegroundColor White
    Write-Host ""

    # Decodificar Access Token
    $accessToken = $response.access_token
    $accessParts = $accessToken.Split('.')

    # Corrigir padding Base64 se necessário
    $base64 = $accessParts[1]
    $base64 = $base64.Replace('-', '+').Replace('_', '/')
    switch ($base64.Length % 4) {
        2 { $base64 += "==" }
        3 { $base64 += "=" }
    }

    $accessPayloadJson = [System.Text.Encoding]::UTF8.GetString([System.Convert]::FromBase64String($base64))
    $accessPayload = $accessPayloadJson | ConvertFrom-Json

    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host "  ACCESS TOKEN (roles e permissões)    " -ForegroundColor Cyan
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host ""

    # Verificar resource_access
    if ($accessPayload.resource_access) {
        Write-Host "✅ resource_access encontrado!" -ForegroundColor Green
        Write-Host ""

        # Converter para JSON formatado
        $resourceAccessJson = $accessPayload.resource_access | ConvertTo-Json -Depth 10
        Write-Host $resourceAccessJson -ForegroundColor Gray
        Write-Host ""

        # Verificar especificamente task-controller
        if ($accessPayload.resource_access.'task-controller') {
            Write-Host "✅ task-controller encontrado!" -ForegroundColor Green
            Write-Host ""

            $tcRoles = $accessPayload.resource_access.'task-controller'.roles
            if ($tcRoles) {
                Write-Host "🎯 ROLES ENCONTRADAS:" -ForegroundColor Yellow
                Write-Host ""
                foreach ($role in $tcRoles) {
                    if ($role -eq "admin") {
                        Write-Host "  • " -NoNewline -ForegroundColor Gray
                        Write-Host "$role" -ForegroundColor DarkYellow
                        Write-Host "    → Badge: 🛡️  ADMINISTRADOR (laranja)" -ForegroundColor DarkYellow
                    } else {
                        Write-Host "  • " -NoNewline -ForegroundColor Gray
                        Write-Host "$role" -ForegroundColor Cyan
                        Write-Host "    → Badge: 👤 USUÁRIO (azul)" -ForegroundColor Cyan
                    }
                }
                Write-Host ""
            } else {
                Write-Host "❌ Nenhuma role encontrada em task-controller!" -ForegroundColor Red
            }
        } else {
            Write-Host "❌ task-controller NÃO encontrado em resource_access!" -ForegroundColor Red
            Write-Host ""
            Write-Host "Clientes disponíveis:" -ForegroundColor Yellow
            $accessPayload.resource_access.PSObject.Properties | ForEach-Object {
                Write-Host "  • $($_.Name)" -ForegroundColor Gray
            }
        }
    } else {
        Write-Host "❌ resource_access NÃO encontrado no access_token!" -ForegroundColor Red
    }

    Write-Host ""

    # Verificar realm_access
    if ($accessPayload.realm_access) {
        Write-Host "✅ realm_access encontrado!" -ForegroundColor Green
        Write-Host ""

        $realmAccessJson = $accessPayload.realm_access | ConvertTo-Json -Depth 10
        Write-Host $realmAccessJson -ForegroundColor Gray
        Write-Host ""
    } else {
        Write-Host "⚠️  realm_access não encontrado (isso é normal)" -ForegroundColor Yellow
        Write-Host ""
    }

    # Resumo
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host "  RESUMO                                " -ForegroundColor Cyan
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host ""

    $hasResourceAccess = $null -ne $accessPayload.resource_access
    $hasTaskController = $null -ne $accessPayload.resource_access.'task-controller'
    $hasRoles = $null -ne $accessPayload.resource_access.'task-controller'.roles

    Write-Host "resource_access presente: " -NoNewline -ForegroundColor Yellow
    if ($hasResourceAccess) { Write-Host "✅ SIM" -ForegroundColor Green } else { Write-Host "❌ NÃO" -ForegroundColor Red }

    Write-Host "task-controller presente: " -NoNewline -ForegroundColor Yellow
    if ($hasTaskController) { Write-Host "✅ SIM" -ForegroundColor Green } else { Write-Host "❌ NÃO" -ForegroundColor Red }

    Write-Host "roles presente: " -NoNewline -ForegroundColor Yellow
    if ($hasRoles) { Write-Host "✅ SIM" -ForegroundColor Green } else { Write-Host "❌ NÃO" -ForegroundColor Red }

    Write-Host ""

    if ($hasResourceAccess -and $hasTaskController -and $hasRoles) {
        Write-Host "🎉 ESTRUTURA CORRETA!" -ForegroundColor Green
        Write-Host ""
        Write-Host "O Keycloak está retornando roles corretamente." -ForegroundColor Gray
        Write-Host "Se o badge não aparece, o problema está no Laravel." -ForegroundColor Gray
        Write-Host ""
        Write-Host "Próximo passo:" -ForegroundColor Yellow
        Write-Host "  1. Faça logout no Laravel" -ForegroundColor Cyan
        Write-Host "  2. Limpe cookies (Ctrl+Shift+Delete)" -ForegroundColor Cyan
        Write-Host "  3. Faça login novamente" -ForegroundColor Cyan
        Write-Host "  4. Verifique: http://localhost:8000/debug-session.php" -ForegroundColor Cyan
    } else {
        Write-Host "❌ PROBLEMA ENCONTRADO!" -ForegroundColor Red
        Write-Host ""
        Write-Host "O Keycloak NÃO está retornando roles corretamente." -ForegroundColor Gray
        Write-Host ""
        Write-Host "Soluções:" -ForegroundColor Yellow
        Write-Host ""

        if (-not $hasResourceAccess) {
            Write-Host "  1. Verificar Client Scopes no Keycloak" -ForegroundColor Cyan
            Write-Host "     • Admin Console → Clients → task-controller → Client Scopes" -ForegroundColor Gray
            Write-Host "     • Adicionar 'roles' aos Assigned Default Client Scopes" -ForegroundColor Gray
        }

        if (-not $hasTaskController) {
            Write-Host "  2. Criar Role Mapping no Client" -ForegroundColor Cyan
            Write-Host "     • Admin Console → Clients → task-controller → Roles" -ForegroundColor Gray
            Write-Host "     • Criar role 'admin'" -ForegroundColor Gray
            Write-Host "     • Atribuir ao usuário em Users → Role Mapping" -ForegroundColor Gray
        }

        if (-not $hasRoles) {
            Write-Host "  3. Atribuir role ao usuário" -ForegroundColor Cyan
            Write-Host "     • Admin Console → Users → administrador → Role Mapping" -ForegroundColor Gray
            Write-Host "     • Filter by clients → task-controller" -ForegroundColor Gray
            Write-Host "     • Assign role 'admin'" -ForegroundColor Gray
        }
    }

    Write-Host ""
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host "  PAYLOAD COMPLETO (para debug)        " -ForegroundColor Cyan
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Access Token Payload:" -ForegroundColor Yellow
    Write-Host ($accessPayloadJson | ConvertFrom-Json | ConvertTo-Json -Depth 10) -ForegroundColor DarkGray
    Write-Host ""

} catch {
    Write-Host "❌ ERRO ao obter token!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Detalhes: " -ForegroundColor Yellow
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""

    if ($_.Exception.Message -match "401") {
        Write-Host "Causa provável: Credenciais inválidas" -ForegroundColor Yellow
    } elseif ($_.Exception.Message -match "404") {
        Write-Host "Causa provável: Keycloak não está rodando ou URL incorreta" -ForegroundColor Yellow
    } else {
        Write-Host "Verifique se:" -ForegroundColor Yellow
        Write-Host "  • Keycloak está rodando (http://localhost:8080)" -ForegroundColor Cyan
        Write-Host "  • Realm 'task-controller' existe" -ForegroundColor Cyan
        Write-Host "  • Client 'task-controller' está configurado" -ForegroundColor Cyan
        Write-Host "  • Client Secret está correto" -ForegroundColor Cyan
    }
}

Write-Host ""
Write-Host "Pressione qualquer tecla para sair..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

