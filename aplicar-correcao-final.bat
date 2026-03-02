@echo off
chcp 65001 >nul
echo.
echo =========================================
echo   CORREÇÃO FINAL: Badge de Perfil
echo =========================================
echo.
echo 🔧 Limpando cache do Laravel...
echo.

cd laravel

call php artisan cache:clear >nul 2>&1
call php artisan config:clear >nul 2>&1
call php artisan view:clear >nul 2>&1
call php artisan route:clear >nul 2>&1

echo ✅ Cache limpo!
echo.

echo 🗑️ Limpando sessões antigas...
echo.

if exist "storage\framework\sessions" (
    del /Q storage\framework\sessions\* >nul 2>&1
    echo ✅ Sessões limpas!
) else (
    echo ℹ️  Pasta de sessões não encontrada
)

echo.
echo =========================================
echo   ✅ CORREÇÃO APLICADA
echo =========================================
echo.
echo O PROBLEMA:
echo   ❌ Código buscava: resource_access['task-controller']
echo   ❌ Config tinha:   KEYCLOAK_CLIENT_ID=task-app
echo   ❌ Resultado:      Roles não eram encontradas!
echo.
echo A SOLUÇÃO:
echo   ✅ Código agora usa: config('keycloak.client_id')
echo   ✅ Lê do .env:      task-app
echo   ✅ Resultado:       Roles encontradas corretamente!
echo.
echo =========================================
echo   🚀 PRÓXIMOS PASSOS
echo =========================================
echo.
echo IMPORTANTE: Você PRECISA fazer logout/login!
echo.
echo 1. No navegador, clique em "Sair"
echo 2. Pressione Ctrl+Shift+Delete
echo 3. Limpe:
echo    - Cookies
echo    - Cache
echo 4. FECHE o navegador completamente
echo 5. Abra novamente
echo 6. Acesse: http://localhost:8000/login
echo 7. Login: administrador@example.com
echo.
echo =========================================
echo   🎯 RESULTADO ESPERADO
echo =========================================
echo.
echo Badge no header:
echo   🛡️  ADMINISTRADOR (gradiente laranja)
echo.
echo =========================================
echo.
echo 📚 Documentação:
echo    - LEIA_AGORA_CORRECAO_BADGE.md
echo    - CORRECAO_CLIENT_ID_BADGE.md
echo.
echo 🐛 Debug (após login):
echo    http://localhost:8000/debug-session.php
echo.
echo =========================================
echo.
pause

