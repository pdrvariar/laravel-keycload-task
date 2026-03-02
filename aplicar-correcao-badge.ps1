# Script de Correção Definitiva: Badge Administrador
# Este script força a limpeza completa e reinicia o servidor

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  CORREÇÃO DEFINITIVA: Badge Admin      " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

$laravelPath = "C:\MyDev\Projetos\task-controller\laravel"
Set-Location $laravelPath

Write-Host "📍 Diretório: $laravelPath" -ForegroundColor Gray
Write-Host ""

# Passo 1: Limpar TUDO
Write-Host "🧹 Passo 1: Limpando TUDO..." -ForegroundColor Yellow
Write-Host ""

$commands = @(
    "cache:clear",
    "config:clear",
    "view:clear",
    "route:clear"
)

foreach ($cmd in $commands) {
    try {
        $result = php artisan $cmd 2>&1
        Write-Host "  ✓ $cmd" -ForegroundColor Green
    } catch {
        Write-Host "  ⚠ $cmd falhou: $_" -ForegroundColor DarkYellow
    }
}

# Limpar sessions manualmente
Write-Host ""
Write-Host "🗑️  Limpando arquivos de sessão..." -ForegroundColor Yellow
$sessionPath = "$laravelPath\storage\framework\sessions"
if (Test-Path $sessionPath) {
    Get-ChildItem $sessionPath -File | Remove-Item -Force
    Write-Host "  ✓ Sessões limpas" -ForegroundColor Green
} else {
    Write-Host "  ℹ Pasta de sessões não encontrada" -ForegroundColor Gray
}

Write-Host ""
Write-Host "✅ Limpeza completa!" -ForegroundColor Green
Write-Host ""

# Passo 2: Mostrar o que foi corrigido
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  O QUE FOI CORRIGIDO                   " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "❌ ANTES: Sistema lia ID Token (sem roles)" -ForegroundColor Red
Write-Host "✅ AGORA: Sistema lê Access Token (com roles)" -ForegroundColor Green
Write-Host ""
Write-Host "Resultado:" -ForegroundColor Yellow
Write-Host "  • Admin agora mostra: " -NoNewline
Write-Host "🛡️  ADMINISTRADOR" -ForegroundColor DarkYellow
Write-Host "  • User agora mostra: " -NoNewline
Write-Host "👤 USUÁRIO" -ForegroundColor Cyan
Write-Host ""

# Passo 3: Instruções de teste
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  INSTRUÇÕES DE TESTE                   " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "⚠️  IMPORTANTE: Você DEVE fazer logout/login!" -ForegroundColor Yellow
Write-Host ""
Write-Host "A sessão antiga ainda tem dados incorretos." -ForegroundColor Gray
Write-Host "Sem logout/login, o badge continuará errado!" -ForegroundColor Gray
Write-Host ""

Write-Host "Passos necessários:" -ForegroundColor Cyan
Write-Host ""
Write-Host "  1️⃣  No navegador, clique em 'Sair'" -ForegroundColor White
Write-Host "  2️⃣  Pressione Ctrl+Shift+Delete" -ForegroundColor White
Write-Host "  3️⃣  Limpe 'Cookies' e 'Cache'" -ForegroundColor White
Write-Host "  4️⃣  Feche TODAS as janelas do navegador" -ForegroundColor White
Write-Host "  5️⃣  Abra o navegador novamente" -ForegroundColor White
Write-Host "  6️⃣  Acesse: http://localhost:8000/login" -ForegroundColor White
Write-Host "  7️⃣  Login: administrador@example.com" -ForegroundColor White
Write-Host ""

# Verificar se servidor está rodando
Write-Host "🔍 Verificando servidor..." -ForegroundColor Yellow
Write-Host ""

try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000" -Method GET -TimeoutSec 2 -ErrorAction Stop
    Write-Host "✓ Servidor Laravel ONLINE em http://localhost:8000" -ForegroundColor Green
    Write-Host ""
    Write-Host "Abrir página de login agora? (S/N): " -ForegroundColor Yellow -NoNewline
    $resposta = Read-Host

    if ($resposta -eq "S" -or $resposta -eq "s") {
        Start-Process "http://localhost:8000/login"
        Write-Host "✓ Página de login aberta" -ForegroundColor Green
    }
} catch {
    Write-Host "⚠ Servidor NÃO está rodando" -ForegroundColor Red
    Write-Host ""
    Write-Host "Iniciar servidor agora? (S/N): " -ForegroundColor Yellow -NoNewline
    $resposta = Read-Host

    if ($resposta -eq "S" -or $resposta -eq "s") {
        Write-Host ""
        Write-Host "🚀 Iniciando servidor Laravel..." -ForegroundColor Cyan
        Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$laravelPath'; Write-Host 'Servidor Laravel' -ForegroundColor Green; Write-Host 'http://localhost:8000' -ForegroundColor Cyan; Write-Host ''; php artisan serve"
        Write-Host "✓ Servidor iniciado em nova janela" -ForegroundColor Green
        Write-Host ""
        Start-Sleep -Seconds 3

        Write-Host "Abrir página de login? (S/N): " -ForegroundColor Yellow -NoNewline
        $resposta2 = Read-Host

        if ($resposta2 -eq "S" -or $resposta2 -eq "s") {
            Start-Process "http://localhost:8000/login"
            Write-Host "✓ Página de login aberta" -ForegroundColor Green
        }
    }
}

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  RESULTADO ESPERADO                    " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "Após fazer login com administrador@example.com:" -ForegroundColor Gray
Write-Host ""
Write-Host "  ┌─────────────────────────────────────┐" -ForegroundColor DarkGray
Write-Host "  │ Pablo Rattes                        │" -ForegroundColor Gray
Write-Host "  │ administrador@example.com           │" -ForegroundColor Gray
Write-Host "  │ " -NoNewline -ForegroundColor Gray
Write-Host "[🛡️  ADMINISTRADOR]" -ForegroundColor DarkYellow -NoNewline
Write-Host "                 │" -ForegroundColor Gray
Write-Host "  │ " -NoNewline -ForegroundColor Gray
Write-Host "  ← Gradiente Laranja/Âmbar" -ForegroundColor DarkYellow -NoNewline
Write-Host "       │" -ForegroundColor Gray
Write-Host "  │ " -NoNewline -ForegroundColor Gray
Write-Host "  ← Ícone animado (pulse)" -ForegroundColor DarkYellow -NoNewline
Write-Host "         │" -ForegroundColor Gray
Write-Host "  └─────────────────────────────────────┘" -ForegroundColor DarkGray
Write-Host ""

Write-Host "Características do badge:" -ForegroundColor Cyan
Write-Host "  ✓ Cor: Laranja/Âmbar (gradiente)" -ForegroundColor Gray
Write-Host "  ✓ Texto: ADMINISTRADOR (caixa alta)" -ForegroundColor Gray
Write-Host "  ✓ Ícone: 🛡️ escudo com check" -ForegroundColor Gray
Write-Host "  ✓ Animação: Pulse suave no ícone" -ForegroundColor Gray
Write-Host "  ✓ Hover: Badge eleva (-1px)" -ForegroundColor Gray
Write-Host ""

# Verificar logs
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  LOGS PARA VERIFICAÇÃO                 " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

$logFile = "$laravelPath\storage\logs\laravel.log"

if (Test-Path $logFile) {
    Write-Host "Após fazer login, verifique os logs:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Procure por 'Login - Roles encontradas':" -ForegroundColor Gray
    Write-Host ""

    # Mostrar últimas linhas
    $lastLines = Get-Content $logFile -Tail 20
    $found = $false

    foreach ($line in $lastLines) {
        if ($line -match "Login - Roles") {
            $found = $true
            Write-Host $line -ForegroundColor Green
        } elseif ($found -and $line -match "admin") {
            Write-Host $line -ForegroundColor Green
        }
    }

    if (-not $found) {
        Write-Host "ℹ Nenhum login recente encontrado nos logs" -ForegroundColor Gray
        Write-Host "Faça login e execute este script novamente" -ForegroundColor Gray
    }
} else {
    Write-Host "⚠ Arquivo de log não encontrado" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  TESTE DE VERIFICAÇÃO                  " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "Depois de fazer login, o badge apareceu corretamente?" -ForegroundColor Yellow
Write-Host ""
Write-Host "  Badge mostra 'ADMINISTRADOR' em laranja? (S/N): " -ForegroundColor Cyan -NoNewline
$resultado = Read-Host

if ($resultado -eq "S" -or $resultado -eq "s") {
    Write-Host ""
    Write-Host "🎉🎉🎉 SUCESSO! 🎉🎉🎉" -ForegroundColor Green
    Write-Host ""
    Write-Host "✨ Badge de perfil funcionando perfeitamente!" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Características implementadas:" -ForegroundColor Yellow
    Write-Host "  ✓ Badge destacado com gradiente" -ForegroundColor Gray
    Write-Host "  ✓ Cores diferentes por role" -ForegroundColor Gray
    Write-Host "  ✓ Ícones específicos animados" -ForegroundColor Gray
    Write-Host "  ✓ Hover effect profissional" -ForegroundColor Gray
    Write-Host "  ✓ UX de alto nível" -ForegroundColor Gray
    Write-Host ""
    Write-Host "Admin: 🛡️  ADMINISTRADOR (laranja)" -ForegroundColor DarkYellow
    Write-Host "User:  👤 USUÁRIO (azul)" -ForegroundColor Cyan
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "❌ Badge ainda não aparece corretamente?" -ForegroundColor Red
    Write-Host ""
    Write-Host "Possíveis causas:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "1. Cookies não foram limpos" -ForegroundColor Cyan
    Write-Host "   → Ctrl+Shift+Delete e limpar TUDO" -ForegroundColor Gray
    Write-Host "   → Fechar navegador completamente" -ForegroundColor Gray
    Write-Host ""
    Write-Host "2. Navegador não fechou completamente" -ForegroundColor Cyan
    Write-Host "   → Abrir Task Manager (Ctrl+Shift+Esc)" -ForegroundColor Gray
    Write-Host "   → Fechar TODOS os processos do navegador" -ForegroundColor Gray
    Write-Host "   → Abrir navegador novamente" -ForegroundColor Gray
    Write-Host ""
    Write-Host "3. Não fez logout antes de limpar" -ForegroundColor Cyan
    Write-Host "   → Acessar o sistema" -ForegroundColor Gray
    Write-Host "   → Clicar em 'Sair'" -ForegroundColor Gray
    Write-Host "   → Depois limpar cookies" -ForegroundColor Gray
    Write-Host ""
    Write-Host "📊 Para diagnóstico, acesse:" -ForegroundColor Yellow
    Write-Host "   http://localhost:8000/debug-session.php" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "   Copie toda a saída e compartilhe" -ForegroundColor Gray
    Write-Host ""
}

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  Correção Concluída                    " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "📚 Documentação:" -ForegroundColor Gray
Write-Host "   • CORRECAO_DEFINITIVA_BADGE.md" -ForegroundColor Cyan
Write-Host "   • RESUMO_FINAL_UX_BADGE.md" -ForegroundColor Cyan
Write-Host ""
Write-Host "Boa sorte! 🚀" -ForegroundColor Green
Write-Host ""

