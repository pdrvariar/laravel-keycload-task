# Script de Verificação: Role Admin para administrador@example.com
# Execute este script para verificar se o usuário tem a role correta

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  VERIFICAÇÃO DE ROLE NO KEYCLOAK   " -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

# Configurações
$KEYCLOAK_URL = "http://localhost:8080"
$REALM = "task-controller"
$CLIENT_ID = "task-controller"
$EMAIL = "administrador@example.com"

Write-Host "🔍 Verificando configuração do Keycloak..." -ForegroundColor Yellow
Write-Host ""
Write-Host "URL Keycloak: $KEYCLOAK_URL" -ForegroundColor Gray
Write-Host "Realm: $REALM" -ForegroundColor Gray
Write-Host "Cliente: $CLIENT_ID" -ForegroundColor Gray
Write-Host "Email: $EMAIL" -ForegroundColor Gray
Write-Host ""

# Verificar se o Keycloak está rodando
Write-Host "📡 Testando conexão com Keycloak..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "$KEYCLOAK_URL/health" -Method GET -TimeoutSec 5 -ErrorAction Stop
    Write-Host "✅ Keycloak está online!" -ForegroundColor Green
} catch {
    Write-Host "❌ Keycloak não está acessível em $KEYCLOAK_URL" -ForegroundColor Red
    Write-Host "   Certifique-se de que o Keycloak está rodando." -ForegroundColor Red
    Write-Host ""
    Write-Host "   Para iniciar o Keycloak via Docker:" -ForegroundColor Yellow
    Write-Host "   docker-compose up -d keycloak" -ForegroundColor Cyan
    exit 1
}

Write-Host ""
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  INSTRUÇÕES DE VERIFICAÇÃO MANUAL   " -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "1️⃣  Acessar Keycloak Admin Console:" -ForegroundColor Yellow
Write-Host "   $KEYCLOAK_URL/admin" -ForegroundColor Cyan
Write-Host ""

Write-Host "2️⃣  Fazer login como admin" -ForegroundColor Yellow
Write-Host "   Usuário: admin" -ForegroundColor Cyan
Write-Host "   Senha: [sua senha de admin do Keycloak]" -ForegroundColor Cyan
Write-Host ""

Write-Host "3️⃣  Navegar até o usuário:" -ForegroundColor Yellow
Write-Host "   → Selecionar Realm: $REALM" -ForegroundColor Cyan
Write-Host "   → Menu: Users" -ForegroundColor Cyan
Write-Host "   → Buscar: $EMAIL" -ForegroundColor Cyan
Write-Host "   → Clicar no usuário" -ForegroundColor Cyan
Write-Host ""

Write-Host "4️⃣  Verificar/Adicionar Role:" -ForegroundColor Yellow
Write-Host "   → Aba: Role Mappings" -ForegroundColor Cyan
Write-Host "   → Client Roles: task-controller" -ForegroundColor Cyan
Write-Host "   → Roles Disponíveis: Procure 'admin'" -ForegroundColor Cyan
Write-Host "   → Se 'admin' não está em 'Assigned Roles':" -ForegroundColor Cyan
Write-Host "      • Selecione 'admin' na lista" -ForegroundColor Cyan
Write-Host "      • Clique 'Add selected'" -ForegroundColor Cyan
Write-Host ""

Write-Host "5️⃣  Verificar se a role foi atribuída:" -ForegroundColor Yellow
Write-Host "   → A role 'admin' deve aparecer em 'Assigned Roles'" -ForegroundColor Cyan
Write-Host "   → Effective Roles também deve mostrar 'admin'" -ForegroundColor Cyan
Write-Host ""

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  TESTE NO SISTEMA                   " -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "Após configurar a role no Keycloak:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Limpar cache do Laravel:" -ForegroundColor Cyan
Write-Host "   cd laravel" -ForegroundColor Gray
Write-Host "   php artisan cache:clear" -ForegroundColor Gray
Write-Host "   php artisan config:clear" -ForegroundColor Gray
Write-Host ""

Write-Host "2. Fazer logout completo:" -ForegroundColor Cyan
Write-Host "   → Clicar em 'Sair' no sistema" -ForegroundColor Gray
Write-Host "   → Limpar cookies do navegador (Ctrl+Shift+Delete)" -ForegroundColor Gray
Write-Host ""

Write-Host "3. Fazer login novamente:" -ForegroundColor Cyan
Write-Host "   Email: $EMAIL" -ForegroundColor Gray
Write-Host "   Senha: [senha do usuário]" -ForegroundColor Gray
Write-Host ""

Write-Host "4. Verificar o header:" -ForegroundColor Cyan
Write-Host "   Deve exibir badge laranja:" -ForegroundColor Gray
Write-Host "   🛡️  ADMINISTRADOR" -ForegroundColor DarkYellow
Write-Host ""

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  VERIFICAÇÃO DE TOKEN (AVANÇADO)    " -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "Para verificar o token JWT após login:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Fazer login no sistema" -ForegroundColor Cyan
Write-Host "2. Abrir DevTools (F12)" -ForegroundColor Cyan
Write-Host "3. Aba Application → Storage → Session Storage" -ForegroundColor Cyan
Write-Host "4. Procurar por chaves relacionadas ao Keycloak" -ForegroundColor Cyan
Write-Host "5. Ou verificar o token na meta tag:" -ForegroundColor Cyan
Write-Host "   <meta name='api-token' content='...'>" -ForegroundColor Gray
Write-Host ""

Write-Host "6. Decodificar o token em: https://jwt.io" -ForegroundColor Cyan
Write-Host "   Procurar por:" -ForegroundColor Gray
Write-Host "   {" -ForegroundColor DarkGray
Write-Host "     'resource_access': {" -ForegroundColor DarkGray
Write-Host "       'task-controller': {" -ForegroundColor DarkGray
Write-Host "         'roles': ['admin']  ← DEVE ESTAR AQUI" -ForegroundColor Green
Write-Host "       }" -ForegroundColor DarkGray
Write-Host "     }" -ForegroundColor DarkGray
Write-Host "   }" -ForegroundColor DarkGray
Write-Host ""

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  TROUBLESHOOTING                    " -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "❌ Se o badge NÃO aparecer como ADMINISTRADOR:" -ForegroundColor Red
Write-Host ""
Write-Host "Causa 1: Role não atribuída no Keycloak" -ForegroundColor Yellow
Write-Host "Solução: Seguir passos 1-5 acima" -ForegroundColor Green
Write-Host ""

Write-Host "Causa 2: Cache do Laravel" -ForegroundColor Yellow
Write-Host "Solução:" -ForegroundColor Green
Write-Host "  php artisan cache:clear" -ForegroundColor Cyan
Write-Host "  php artisan config:clear" -ForegroundColor Cyan
Write-Host "  php artisan view:clear" -ForegroundColor Cyan
Write-Host ""

Write-Host "Causa 3: Sessão antiga" -ForegroundColor Yellow
Write-Host "Solução:" -ForegroundColor Green
Write-Host "  1. Fazer logout completo" -ForegroundColor Cyan
Write-Host "  2. Limpar cookies (Ctrl+Shift+Delete)" -ForegroundColor Cyan
Write-Host "  3. Fechar e reabrir navegador" -ForegroundColor Cyan
Write-Host "  4. Fazer login novamente" -ForegroundColor Cyan
Write-Host ""

Write-Host "Causa 4: Realm ou Client incorreto" -ForegroundColor Yellow
Write-Host "Solução:" -ForegroundColor Green
Write-Host "  Verificar .env do Laravel:" -ForegroundColor Cyan
Write-Host "  KEYCLOAK_REALM=task-controller" -ForegroundColor Gray
Write-Host "  KEYCLOAK_CLIENT_ID=task-controller" -ForegroundColor Gray
Write-Host ""

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  PREVIEW DO RESULTADO ESPERADO      " -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "Abra este arquivo no navegador para ver o visual:" -ForegroundColor Yellow
Write-Host "  PREVIEW_UX_BADGE_PERFIL.html" -ForegroundColor Cyan
Write-Host ""

Write-Host "Documentação completa em:" -ForegroundColor Yellow
Write-Host "  MELHORIAS_UX_BADGE_PERFIL.md" -ForegroundColor Cyan
Write-Host ""

Write-Host "=====================================" -ForegroundColor Green
Write-Host "  ✅ Verificação Concluída!          " -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Green
Write-Host ""

# Perguntar se deseja abrir o Keycloak Admin
Write-Host "Deseja abrir o Keycloak Admin Console agora? (S/N): " -ForegroundColor Yellow -NoNewline
$resposta = Read-Host

if ($resposta -eq "S" -or $resposta -eq "s") {
    Write-Host "🚀 Abrindo Keycloak Admin Console..." -ForegroundColor Green
    Start-Process "$KEYCLOAK_URL/admin"
} else {
    Write-Host "👍 OK! Você pode acessar manualmente quando precisar." -ForegroundColor Cyan
}

Write-Host ""
Write-Host "Boa sorte! 🚀" -ForegroundColor Green

