#!/usr/bin/env pwsh
<#
.DESCRIPTION
Script para testar se o header, sidebar e carregamento de tarefas estao funcionando corretamente.
#>

Write-Host "================================" -ForegroundColor Cyan
Write-Host "Teste da Aplicacao Task Controller" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

$baseUrl = "http://localhost:8000"

# Teste 1: Verificar se o servidor Laravel esta rodando
Write-Host "[1/4] Verificando se o servidor Laravel esta rodando..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "$baseUrl" -UseBasicParsing -ErrorAction Stop -TimeoutSec 5
    Write-Host "OK - Servidor esta respondendo (Status: $($response.StatusCode))" -ForegroundColor Green
} catch {
    Write-Host "ERRO - Servidor nao esta respondendo" -ForegroundColor Red
    Write-Host "Erro: $_" -ForegroundColor Red
    exit 1
}

Write-Host ""

# Teste 2: Verificar se o header esta sendo renderizado
Write-Host "[2/4] Verificando se o header esta sendo renderizado..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "$baseUrl/login" -UseBasicParsing
    if ($response.Content -match 'Task Controller') {
        Write-Host "OK - Header encontrado na pagina de login" -ForegroundColor Green
    } else {
        Write-Host "ERRO - Header nao encontrado" -ForegroundColor Red
    }
} catch {
    Write-Host "ERRO - Problema ao acessar pagina de login: $_" -ForegroundColor Red
}

Write-Host ""

# Teste 3: Verificar se os estilos do Blade estao sendo incluidos
Write-Host "[3/4] Verificando se os arquivos de partials estao preenchidos..." -ForegroundColor Yellow
$headerFile = "C:\MyDev\Projetos\task-controller\laravel\resources\views\partials\header.blade.php"
$sidebarFile = "C:\MyDev\Projetos\task-controller\laravel\resources\views\partials\sidebar.blade.php"
$footerFile = "C:\MyDev\Projetos\task-controller\laravel\resources\views\partials\footer.blade.php"

$headerSize = (Get-Item $headerFile).Length
$sidebarSize = (Get-Item $sidebarFile).Length
$footerSize = (Get-Item $footerFile).Length

if ($headerSize -gt 0) {
    Write-Host "OK - Header.blade.php preenchido ($headerSize bytes)" -ForegroundColor Green
} else {
    Write-Host "ERRO - Header.blade.php vazio" -ForegroundColor Red
}

if ($sidebarSize -gt 0) {
    Write-Host "OK - Sidebar.blade.php preenchido ($sidebarSize bytes)" -ForegroundColor Green
} else {
    Write-Host "ERRO - Sidebar.blade.php vazio" -ForegroundColor Red
}

if ($footerSize -gt 0) {
    Write-Host "OK - Footer.blade.php preenchido ($footerSize bytes)" -ForegroundColor Green
} else {
    Write-Host "ERRO - Footer.blade.php vazio" -ForegroundColor Red
}

Write-Host ""

# Teste 4: Verificar rotas da API
Write-Host "[4/4] Verificando se as rotas da API estao configuradas..." -ForegroundColor Yellow
$routeFile = "C:\MyDev\Projetos\task-controller\laravel\routes\api.php"
$routeContent = Get-Content $routeFile -Raw

if ($routeContent -match 'validate\.keycloak\.token') {
    Write-Host "OK - Middleware de validacao de token esta configurado" -ForegroundColor Green
} else {
    Write-Host "ERRO - Middleware nao encontrado nas rotas" -ForegroundColor Red
}

if ($routeContent -match '/tasks') {
    Write-Host "OK - Rota /api/tasks esta configurada" -ForegroundColor Green
} else {
    Write-Host "ERRO - Rota /api/tasks nao encontrada" -ForegroundColor Red
}

Write-Host ""
Write-Host "================================" -ForegroundColor Cyan
Write-Host "Teste concluido!" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Proximos passos:" -ForegroundColor Yellow
Write-Host "1. Abra a aplicacao no navegador: $baseUrl" -ForegroundColor White
Write-Host "2. Faca o login com suas credenciais do Keycloak" -ForegroundColor White
Write-Host "3. Verifique se o header, sidebar e tarefas aparecem corretamente" -ForegroundColor White

