# Script de Debug e Correção: Badge Administrador
# Execute este script para diagnosticar e corrigir o problema

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  DEBUG: Badge Administrador            " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# Ir para o diretório do Laravel
$laravelPath = "C:\MyDev\Projetos\task-controller\laravel"
Set-Location $laravelPath

Write-Host "📍 Diretório: $laravelPath" -ForegroundColor Gray
Write-Host ""

# Passo 1: Limpar todo o cache
Write-Host "🧹 Passo 1: Limpando cache..." -ForegroundColor Yellow
Write-Host ""

try {
    php artisan cache:clear 2>&1 | Out-Null
    Write-Host "  ✓ Cache limpo" -ForegroundColor Green
} catch {
    Write-Host "  ⚠ Aviso ao limpar cache: $_" -ForegroundColor DarkYellow
}

try {
    php artisan config:clear 2>&1 | Out-Null
    Write-Host "  ✓ Config limpo" -ForegroundColor Green
} catch {
    Write-Host "  ⚠ Aviso ao limpar config: $_" -ForegroundColor DarkYellow
}

try {
    php artisan view:clear 2>&1 | Out-Null
    Write-Host "  ✓ Views limpas" -ForegroundColor Green
} catch {
    Write-Host "  ⚠ Aviso ao limpar views: $_" -ForegroundColor DarkYellow
}

try {
    php artisan route:clear 2>&1 | Out-Null
    Write-Host "  ✓ Routes limpas" -ForegroundColor Green
} catch {
    Write-Host "  ⚠ Aviso ao limpar routes: $_" -ForegroundColor DarkYellow
}

Write-Host ""
Write-Host "✅ Cache completamente limpo!" -ForegroundColor Green
Write-Host ""

# Passo 2: Verificar se o servidor está rodando
Write-Host "🔍 Passo 2: Verificando servidor..." -ForegroundColor Yellow
Write-Host ""

try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000" -Method GET -TimeoutSec 2 -ErrorAction Stop
    Write-Host "  ✓ Servidor Laravel está ONLINE em http://localhost:8000" -ForegroundColor Green
} catch {
    Write-Host "  ⚠ Servidor não está rodando" -ForegroundColor DarkYellow
    Write-Host ""
    Write-Host "  Iniciar servidor agora? (S/N): " -ForegroundColor Yellow -NoNewline
    $resposta = Read-Host

    if ($resposta -eq "S" -or $resposta -eq "s") {
        Write-Host ""
        Write-Host "  🚀 Iniciando servidor Laravel..." -ForegroundColor Cyan
        Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$laravelPath'; php artisan serve"
        Write-Host "  ✓ Servidor iniciado em nova janela" -ForegroundColor Green
        Start-Sleep -Seconds 3
    }
}

Write-Host ""

# Passo 3: Abrir página de debug
Write-Host "🔬 Passo 3: Diagnóstico da Sessão" -ForegroundColor Yellow
Write-Host ""
Write-Host "Vou abrir a página de debug da sessão..." -ForegroundColor Gray
Write-Host "URL: http://localhost:8000/debug-session.php" -ForegroundColor Cyan
Write-Host ""
Write-Host "Abrir agora? (S/N): " -ForegroundColor Yellow -NoNewline
$resposta = Read-Host

if ($resposta -eq "S" -or $resposta -eq "s") {
    Start-Process "http://localhost:8000/debug-session.php"
    Write-Host "✓ Página de debug aberta no navegador" -ForegroundColor Green
    Write-Host ""
    Write-Host "⚠️  IMPORTANTE: Você está logado no sistema?" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Se NÃO:" -ForegroundColor Red
    Write-Host "  1. Faça login primeiro: http://localhost:8000/login" -ForegroundColor Cyan
    Write-Host "  2. Email: administrador@example.com" -ForegroundColor Cyan
    Write-Host "  3. Depois volte ao debug: http://localhost:8000/debug-session.php" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Pressione ENTER depois de verificar a página de debug..." -NoNewline
    Read-Host
}

Write-Host ""

# Passo 4: Instruções de logout/login
Write-Host "🔄 Passo 4: Logout e Login Limpo" -ForegroundColor Yellow
Write-Host ""
Write-Host "Para garantir que tudo funcione, faça:" -ForegroundColor Gray
Write-Host ""
Write-Host "  1. Clique em 'Sair' no sistema" -ForegroundColor Cyan
Write-Host "  2. Pressione Ctrl+Shift+Delete" -ForegroundColor Cyan
Write-Host "  3. Limpe cookies e cache do navegador" -ForegroundColor Cyan
Write-Host "  4. Feche TODAS as janelas do navegador" -ForegroundColor Cyan
Write-Host "  5. Abra o navegador novamente" -ForegroundColor Cyan
Write-Host "  6. Acesse: http://localhost:8000/login" -ForegroundColor Cyan
Write-Host "  7. Login: administrador@example.com" -ForegroundColor Cyan
Write-Host ""
Write-Host "Pressione ENTER quando tiver feito logout/login..." -NoNewline
Read-Host

Write-Host ""

# Passo 5: Verificar logs
Write-Host "📋 Passo 5: Verificando Logs" -ForegroundColor Yellow
Write-Host ""

$logFile = "$laravelPath\storage\logs\laravel.log"

if (Test-Path $logFile) {
    Write-Host "Últimas linhas do log (procure por 'Header Debug'):" -ForegroundColor Gray
    Write-Host ""
    Write-Host "========== LOG ==========" -ForegroundColor DarkGray
    Get-Content $logFile -Tail 30 | ForEach-Object {
        if ($_ -match "Header Debug") {
            Write-Host $_ -ForegroundColor Green
        } elseif ($_ -match "ERROR") {
            Write-Host $_ -ForegroundColor Red
        } elseif ($_ -match "WARNING") {
            Write-Host $_ -ForegroundColor Yellow
        } else {
            Write-Host $_ -ForegroundColor Gray
        }
    }
    Write-Host "=========================" -ForegroundColor DarkGray
} else {
    Write-Host "⚠️  Arquivo de log não encontrado" -ForegroundColor Yellow
}

Write-Host ""

# Passo 6: Verificação final
Write-Host "✅ Passo 6: Verificação Final" -ForegroundColor Yellow
Write-Host ""
Write-Host "Agora verifique o header do sistema:" -ForegroundColor Gray
Write-Host ""
Write-Host "  O badge deve mostrar:" -ForegroundColor Cyan
Write-Host ""
Write-Host "  ┌─────────────────────────────────┐" -ForegroundColor DarkGray
Write-Host "  │ Admin User                      │" -ForegroundColor Gray
Write-Host "  │ administrador@example.com       │" -ForegroundColor Gray
Write-Host "  │ " -NoNewline -ForegroundColor Gray
Write-Host "[🛡️  ADMINISTRADOR]" -ForegroundColor DarkYellow -NoNewline
Write-Host "           │" -ForegroundColor Gray
Write-Host "  │ " -NoNewline -ForegroundColor Gray
Write-Host "  ← Laranja/Âmbar" -ForegroundColor DarkYellow -NoNewline
Write-Host "              │" -ForegroundColor Gray
Write-Host "  └─────────────────────────────────┘" -ForegroundColor DarkGray
Write-Host ""

Write-Host "O badge apareceu corretamente? (S/N): " -ForegroundColor Yellow -NoNewline
$resposta = Read-Host

if ($resposta -eq "S" -or $resposta -eq "s") {
    Write-Host ""
    Write-Host "🎉 SUCESSO! Badge funcionando!" -ForegroundColor Green
    Write-Host ""
    Write-Host "✨ O sistema agora mostra:" -ForegroundColor Cyan
    Write-Host "   • Nome do usuário" -ForegroundColor Gray
    Write-Host "   • Email do usuário" -ForegroundColor Gray
    Write-Host "   • Badge de perfil destacado" -ForegroundColor Gray
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "❌ Badge ainda não aparece?" -ForegroundColor Red
    Write-Host ""
    Write-Host "Possíveis causas:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "1. Sessão ainda em cache" -ForegroundColor Cyan
    Write-Host "   Solução: Limpar cookies E fechar navegador completamente" -ForegroundColor Gray
    Write-Host ""
    Write-Host "2. Roles não estão no token" -ForegroundColor Cyan
    Write-Host "   Solução: Verificar página /debug-session.php" -ForegroundColor Gray
    Write-Host "   Procurar por 'resource_access' → 'task-controller' → 'roles'" -ForegroundColor Gray
    Write-Host ""
    Write-Host "3. Nome do client diferente" -ForegroundColor Cyan
    Write-Host "   Solução: Ver no debug qual é o nome real do client" -ForegroundColor Gray
    Write-Host "   Pode não ser 'task-controller'" -ForegroundColor Gray
    Write-Host ""
    Write-Host "📊 Informações para análise:" -ForegroundColor Yellow
    Write-Host "   • Acesse: http://localhost:8000/debug-session.php" -ForegroundColor Cyan
    Write-Host "   • Copie toda a saída" -ForegroundColor Cyan
    Write-Host "   • Procure por 'resource_access'" -ForegroundColor Cyan
    Write-Host "   • Veja qual é o nome real do client" -ForegroundColor Cyan
    Write-Host ""
}

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  Debug Concluído                       " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "📚 Documentação disponível em:" -ForegroundColor Gray
Write-Host "   • DEBUG_BADGE_ADMINISTRADOR.md" -ForegroundColor Cyan
Write-Host "   • RESUMO_FINAL_UX_BADGE.md" -ForegroundColor Cyan
Write-Host ""
Write-Host "💡 Dica: Se ainda não funcionar, compartilhe a saída de:" -ForegroundColor Yellow
Write-Host "   http://localhost:8000/debug-session.php" -ForegroundColor Cyan
Write-Host ""

