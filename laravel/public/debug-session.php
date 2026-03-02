<!DOCTYPE html>
<html>
<head>
    <title>Debug - Sessão Keycloak</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 2rem;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #252526;
            padding: 2rem;
            border-radius: 8px;
        }
        h1 {
            color: #4ec9b0;
            border-bottom: 2px solid #4ec9b0;
            padding-bottom: 1rem;
        }
        h2 {
            color: #569cd6;
            margin-top: 2rem;
        }
        pre {
            background: #1e1e1e;
            padding: 1rem;
            border-radius: 4px;
            border-left: 4px solid #4ec9b0;
            overflow-x: auto;
        }
        .error {
            background: #5a1d1d;
            border-left-color: #f48771;
            color: #f48771;
        }
        .success {
            background: #1a3a1a;
            border-left-color: #4ec9b0;
            color: #4ec9b0;
        }
        .warning {
            background: #3a3a1a;
            border-left-color: #dcdcaa;
            color: #dcdcaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Debug - Sessão Keycloak</h1>

        <h2>📊 Dados na Sessão</h2>
        <pre><?php
            session_start();
            echo "=== SESSION DATA ===\n";
            print_r($_SESSION);
        ?></pre>

        <h2>🔑 Token de Acesso</h2>
        <pre><?php
            $token = $_SESSION['keycloak_access_token'] ?? null;
            if ($token) {
                echo "Token existe: ✓\n";
                echo "Tamanho: " . strlen($token) . " caracteres\n\n";

                // Decodificar JWT
                $parts = explode('.', $token);
                if (count($parts) === 3) {
                    $payload = json_decode(base64_decode($parts[1]), true);
                    echo "=== PAYLOAD DECODIFICADO ===\n";
                    print_r($payload);
                } else {
                    echo "❌ Token JWT inválido\n";
                }
            } else {
                echo "❌ Token não encontrado na sessão\n";
            }
        ?></pre>

        <h2>👤 Dados do Usuário (keycloak_user)</h2>
        <pre><?php
            $user = $_SESSION['keycloak_user'] ?? null;
            if ($user) {
                echo "=== KEYCLOAK USER ===\n";
                print_r($user);

                echo "\n=== VERIFICAÇÃO DE ROLES ===\n";

                // Verificar resource_access
                if (isset($user['resource_access'])) {
                    echo "✓ resource_access existe\n";

                    if (isset($user['resource_access']['task-controller'])) {
                        echo "✓ task-controller existe\n";

                        if (isset($user['resource_access']['task-controller']['roles'])) {
                            echo "✓ roles existe\n";
                            echo "Roles: ";
                            print_r($user['resource_access']['task-controller']['roles']);
                        } else {
                            echo "❌ roles NÃO existe em resource_access.task-controller\n";
                        }
                    } else {
                        echo "❌ task-controller NÃO existe em resource_access\n";
                        echo "Clients disponíveis: ";
                        print_r(array_keys($user['resource_access']));
                    }
                } else {
                    echo "❌ resource_access NÃO existe\n";
                }
            } else {
                echo "❌ keycloak_user não encontrado na sessão\n";
            }
        ?></pre>

        <h2>🧪 Teste de Acesso (como no Blade)</h2>
        <pre class="<?php
            $roles = $_SESSION['keycloak_user']['resource_access']['task-controller']['roles'] ?? [];
            echo !empty($roles) && in_array('admin', $roles) ? 'success' : 'error';
        ?>"><?php
            echo "session('keycloak_user.resource_access.task-controller.roles'):\n";
            print_r($roles);

            if (!empty($roles)) {
                echo "\n✓ Roles encontradas!\n";
                if (in_array('admin', $roles)) {
                    echo "✓ Role 'admin' ESTÁ PRESENTE\n";
                    echo "Badge deveria mostrar: ADMINISTRADOR (laranja)\n";
                } else {
                    echo "⚠ Role 'admin' NÃO encontrada\n";
                    echo "Roles disponíveis: " . implode(', ', $roles) . "\n";
                }
            } else {
                echo "\n❌ Nenhuma role encontrada\n";
                echo "Badge mostrará: USUÁRIO (azul) como fallback\n";
            }
        ?></pre>

        <h2>🔧 Ação Recomendada</h2>
        <pre><?php
            if (empty($roles)) {
                echo "❌ PROBLEMA IDENTIFICADO: Roles não estão na sessão\n\n";
                echo "Soluções:\n";
                echo "1. Fazer logout completo\n";
                echo "2. Limpar cookies do navegador\n";
                echo "3. Fazer login novamente\n";
                echo "4. Verificar se o Keycloak está retornando as roles no token\n";
            } else {
                echo "✓ Roles estão presentes na sessão!\n";
                echo "Se o badge não aparece, verifique:\n";
                echo "1. Cache do Laravel (php artisan view:clear)\n";
                echo "2. CSS carregado corretamente\n";
                echo "3. JavaScript não está com erro\n";
            }
        ?></pre>
    </div>
</body>
</html>

