# Script de Teste: Verificar Correção de Badge
# Verifica se o client_id está correto em todos os arquivos

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  VERIFICAÇÃO: Correção de Badge        " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

$basePath = "C:\MyDev\Projetos\task-controller\laravel"
$allPassed = $true

# Teste 1: Verificar .env
Write-Host "1️⃣  Verificando .env..." -ForegroundColor Yellow
$envPath = "$basePath\.env"
if (Test-Path $envPath) {
    $envContent = Get-Content $envPath -Raw
    if ($envContent -match 'KEYCLOAK_CLIENT_ID=(.+)') {
        $clientId = $matches[1].Trim()
        Write-Host "   ✓ CLIENT_ID encontrado: " -NoNewline -ForegroundColor Green
        Write-Host $clientId -ForegroundColor Cyan
    } else {
        Write-Host "   ❌ CLIENT_ID não encontrado no .env" -ForegroundColor Red
        $allPassed = $false
    }
} else {
    Write-Host "   ❌ Arquivo .env não encontrado" -ForegroundColor Red
    $allPassed = $false
}
Write-Host ""

# Teste 2: Verificar se arquivos usam config()
Write-Host "2️⃣  Verificando arquivos corrigidos..." -ForegroundColor Yellow

$arquivos = @(
    @{
        Path = "app\Http\Controllers\AuthController.php"
        Pattern = "config\('keycloak\.client_id'"
        Name = "AuthController"
    },
    @{
        Path = "routes\web.php"
        Pattern = "config\('keycloak\.client_id'"
        Name = "web.php"
    },
    @{
        Path = "resources\views\partials\header.blade.php"
        Pattern = "config\('keycloak\.client_id'"
        Name = "header.blade.php"
    },
    @{
        Path = "resources\views\partials\sidebar.blade.php"
        Pattern = "config\('keycloak\.client_id'"
        Name = "sidebar.blade.php"
    }
)

foreach ($arquivo in $arquivos) {
    $fullPath = Join-Path $basePath $arquivo.Path
    if (Test-Path $fullPath) {
        $content = Get-Content $fullPath -Raw
        if ($content -match $arquivo.Pattern) {
            Write-Host "   ✓ $($arquivo.Name)" -ForegroundColor Green
        } else {
            Write-Host "   ❌ $($arquivo.Name) - NÃO usa config()" -ForegroundColor Red
            $allPassed = $false
        }
    } else {
        Write-Host "   ⚠ $($arquivo.Name) - Arquivo não encontrado" -ForegroundColor Yellow
    }
}
Write-Host ""

# Teste 3: Verificar se não há hardcoded 'task-controller'
Write-Host "3️⃣  Verificando referências hardcoded..." -ForegroundColor Yellow

$problemas = @()

foreach ($arquivo in $arquivos) {
    $fullPath = Join-Path $basePath $arquivo.Path
    if (Test-Path $fullPath) {
        $lines = Get-Content $fullPath
        $lineNum = 0
        foreach ($line in $lines) {
            $lineNum++
            if ($line -match "resource_access\]\['task-controller'\]" -or
                $line -match 'resource_access\["task-controller"\]' -or
                $line -match "resource_access\.task-controller") {
                $problemas += @{
                    File = $arquivo.Name
                    Line = $lineNum
                    Content = $line.Trim()
                }
            }
        }
    }
}

if ($problemas.Count -eq 0) {
    Write-Host "   ✓ Nenhuma referência hardcoded encontrada!" -ForegroundColor Green
} else {
    Write-Host "   ❌ Encontradas $($problemas.Count) referências hardcoded:" -ForegroundColor Red
    foreach ($problema in $problemas) {
        Write-Host "      • $($problema.File):$($problema.Line)" -ForegroundColor Yellow
        Write-Host "        $($problema.Content)" -ForegroundColor Gray
    }
    $allPassed = $false
}
Write-Host ""

# Teste 4: Verificar cache limpo
Write-Host "4️⃣  Verificando cache..." -ForegroundColor Yellow
$cacheFiles = @(
    "bootstrap\cache\config.php",
    "bootstrap\cache\routes-v7.php"
)

$cacheProblems = 0
foreach ($cacheFile in $cacheFiles) {
    $fullPath = Join-Path $basePath $cacheFile
    if (Test-Path $fullPath) {
        $cacheProblems++
    }
}

if ($cacheProblems -eq 0) {
    Write-Host "   ✓ Cache limpo!" -ForegroundColor Green
} else {
    Write-Host "   ⚠ $cacheProblems arquivo(s) de cache encontrado(s)" -ForegroundColor Yellow
    Write-Host "     Execute: php artisan config:clear" -ForegroundColor Gray
}
Write-Host ""

# Teste 5: Verificar sessões antigas
Write-Host "5️⃣  Verificando sessões..." -ForegroundColor Yellow
$sessionPath = "$basePath\storage\framework\sessions"
if (Test-Path $sessionPath) {
    $sessionFiles = Get-ChildItem $sessionPath -File
    if ($sessionFiles.Count -eq 0) {
        Write-Host "   ✓ Nenhuma sessão antiga!" -ForegroundColor Green
    } else {
        Write-Host "   ⚠ $($sessionFiles.Count) sessão(ões) encontrada(s)" -ForegroundColor Yellow
        Write-Host "     Faça logout e limpe cookies do navegador" -ForegroundColor Gray
    }
} else {
    Write-Host "   ℹ Pasta de sessões não existe (ok se usando redis/database)" -ForegroundColor Gray
}
Write-Host ""

# Teste 6: Verificar logs recentes
Write-Host "6️⃣  Verificando logs..." -ForegroundColor Yellow
$logPath = "$basePath\storage\logs\laravel.log"
if (Test-Path $logPath) {
    $recentLogs = Get-Content $logPath -Tail 30
    $foundDebug = $false
    $foundClientId = $false

    foreach ($line in $recentLogs) {
        if ($line -match "Header Debug") {
            $foundDebug = $true
        }
        if ($line -match '"client_id"') {
            $foundClientId = $true
        }
    }

    if ($foundDebug -and $foundClientId) {
        Write-Host "   ✓ Logs de debug encontrados!" -ForegroundColor Green
        Write-Host "     Última entrada de Header Debug detectada" -ForegroundColor Gray
    } elseif ($foundDebug) {
        Write-Host "   ⚠ Logs encontrados mas sem client_id" -ForegroundColor Yellow
    } else {
        Write-Host "   ℹ Nenhum login recente detectado" -ForegroundColor Gray
        Write-Host "     Faça login para gerar logs" -ForegroundColor Gray
    }
} else {
    Write-Host "   ℹ Arquivo de log não existe ainda" -ForegroundColor Gray
}
Write-Host ""

# Resumo Final
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  RESULTADO                             " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

if ($allPassed) {
    Write-Host "✅ TODOS OS TESTES PASSARAM!" -ForegroundColor Green
    Write-Host ""
    Write-Host "A correção foi aplicada corretamente." -ForegroundColor Gray
    Write-Host ""
    Write-Host "Próximos passos:" -ForegroundColor Yellow
    Write-Host "  1. Fazer logout no sistema" -ForegroundColor Cyan
    Write-Host "  2. Limpar cookies (Ctrl+Shift+Delete)" -ForegroundColor Cyan
    Write-Host "  3. Fechar navegador completamente" -ForegroundColor Cyan
    Write-Host "  4. Fazer novo login" -ForegroundColor Cyan
    Write-Host "  5. Verificar badge no header" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Badge esperado para admin:" -ForegroundColor Yellow
    Write-Host "  🛡️  ADMINISTRADOR (gradiente laranja)" -ForegroundColor DarkYellow
    Write-Host ""
} else {
    Write-Host "❌ ALGUNS TESTES FALHARAM" -ForegroundColor Red
    Write-Host ""
    Write-Host "Revise os erros acima e corrija." -ForegroundColor Yellow
    Write-Host ""
}

# Teste Adicional: Mostrar configuração atual
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  CONFIGURAÇÃO ATUAL                    " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

if (Test-Path $envPath) {
    $envLines = Get-Content $envPath | Select-String "KEYCLOAK"
    Write-Host "Configurações Keycloak no .env:" -ForegroundColor Yellow
    Write-Host ""
    foreach ($line in $envLines) {
        $lineStr = $line.ToString()
        if ($lineStr -match "CLIENT_ID") {
            Write-Host "  $lineStr" -ForegroundColor Cyan
        } else {
            Write-Host "  $lineStr" -ForegroundColor Gray
        }
    }
}

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Para mais detalhes, consulte:" -ForegroundColor Gray
Write-Host "  CORRECAO_CLIENT_ID_BADGE.md" -ForegroundColor Cyan
Write-Host ""

