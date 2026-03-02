<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Redirect homepage to login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $keycloakUser = session('keycloak_user') ?? [];
        $roles = $keycloakUser['resource_access']['task-controller']['roles'] ?? [];
        if (in_array('admin', $roles)) {
            return redirect('/admin/dashboard');
        }
        return view('user.dashboard');
    })->name('dashboard');

    Route::get('/admin/dashboard', function () {
        $keycloakUser = session('keycloak_user') ?? [];
        $roles = $keycloakUser['resource_access']['task-controller']['roles'] ?? [];
        abort_if(!in_array('admin', $roles), 403);
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Rotas de Tarefas
    Route::get('/tasks', function () {
        return view('tasks.index');
    })->name('tasks.index');

    Route::get('/tasks/create', function () {
        return view('tasks.create');
    })->name('tasks.create');

    Route::get('/tasks/{task}/edit', function () {
        return view('tasks.edit');
    })->name('tasks.edit');

    // Rotas Admin para Tarefas
    Route::get('/admin/tasks', function () {
        $keycloakUser = session('keycloak_user') ?? [];
        $roles = $keycloakUser['resource_access']['task-controller']['roles'] ?? [];
        abort_if(!in_array('admin', $roles), 403);
        return view('admin.tasks.index');
    })->name('admin.tasks.index');
});

// Rota de Ajuda para Configuração do Keycloak
Route::get('/setup/keycloak-key', function () {
    try {
        $internalUrl = config('keycloak.base_url_internal');
        $realm = config('keycloak.realm');
        $url = "{$internalUrl}/realms/{$realm}";

        // Contexto para ignorar SSL em dev e definir timeout
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
            'http' => [
                'timeout' => 5
            ]
        ]);

        $json = @file_get_contents($url, false, $context);

        if ($json === false) {
            throw new \Exception("Não foi possível conectar ao Keycloak em: $url");
        }

        $data = json_decode($json, true);
        $key = $data['public_key'] ?? null;

        if (!$key) {
            return "Chave pública não encontrada na resposta do Keycloak.";
        }

        return "
            <html>
            <body style='font-family: sans-serif; padding: 2rem; max-width: 800px; margin: 0 auto;'>
                <h1 style='color: #d32f2f;'>Ação Necessária: Configuração do Keycloak</h1>
                <p>O erro <code>Argument #2 (\$publicKey) must be of type string</code> ocorre porque o Laravel não tem a chave pública para validar os tokens.</p>

                <h3>Passo 1: Copie a configuração abaixo</h3>
                <div style='background: #f5f5f5; padding: 1.5rem; border-radius: 8px; border: 1px solid #ddd; overflow-x: auto;'>
                    <code style='font-size: 1.1em; color: #2e7d32;'>KEYCLOAK_REALM_PUBLIC_KEY=\"$key\"</code>
                </div>

                <h3>Passo 2: Adicione ao arquivo .env</h3>
                <p>Abra o arquivo <code>.env</code> na pasta <code>laravel/</code> e cole a linha acima no final do arquivo.</p>

                <h3>Passo 3: Reinicie os containers</h3>
                <p>Execute os comandos abaixo no seu terminal:</p>
                <pre style='background: #333; color: #fff; padding: 1rem; border-radius: 4px;'>docker-compose down\ndocker-compose up -d</pre>

                <p><a href='/tasks' style='display: inline-block; margin-top: 1rem; padding: 0.5rem 1rem; background: #1976d2; color: white; text-decoration: none; border-radius: 4px;'>Tentar Novamente</a></p>
            </body>
            </html>
        ";
    } catch (\Exception $e) {
        return "
            <html>
            <body style='font-family: sans-serif; padding: 2rem;'>
                <h1 style='color: #d32f2f;'>Erro ao buscar chave</h1>
                <p>Não foi possível obter a chave automaticamente.</p>
                <p><strong>Erro:</strong> " . $e->getMessage() . "</p>
                <hr>
                <h3>Como resolver manualmente:</h3>
                <ol>
                    <li>Acesse o Keycloak Admin: <a href='http://localhost:8080' target='_blank'>http://localhost:8080</a></li>
                    <li>Vá em <strong>Realm Settings</strong> > <strong>Keys</strong></li>
                    <li>Clique em <strong>Public key</strong> na linha do algoritmo <strong>RS256</strong></li>
                    <li>Copie a chave e adicione ao seu <code>.env</code>: <br><code>KEYCLOAK_REALM_PUBLIC_KEY=\"SUA_CHAVE_AQUI\"</code></li>
                </ol>
            </body>
            </html>
        ";
    }
});
