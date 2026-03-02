<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Teste de Criação de Usuário ===\n\n";

// 1. Verificar configuração do Keycloak
echo "1. Configuração Keycloak:\n";
echo "   LOAD_USER_FROM_DATABASE: " . config('keycloak.load_user_from_database') . "\n";
echo "   USER_PROVIDER_CREDENTIAL: " . config('keycloak.user_provider_credential') . "\n";
echo "   APPEND_DECODED_TOKEN: " . config('keycloak.append_decoded_token') . "\n";
echo "\n";

// 2. Verificar usuários existentes
echo "2. Usuários no banco:\n";
$users = \App\Models\User::all();
if ($users->isEmpty()) {
    echo "   Nenhum usuário encontrado.\n";
} else {
    foreach ($users as $user) {
        echo "   - ID: {$user->id}, Email: {$user->email}, Nome: {$user->name}\n";
    }
}
echo "\n";

// 3. Criar usuário manualmente se não existir
echo "3. Criando usuário admin manualmente...\n";
$adminUser = \App\Models\User::firstOrCreate(
    ['email' => 'admin@example.com'],
    [
        'name' => 'Admin User',
        'password' => bcrypt('admin123'),
    ]
);
echo "   Usuário criado/encontrado: ID={$adminUser->id}, Email={$adminUser->email}\n";
echo "\n";

// 4. Criar uma tarefa de teste
echo "4. Criando tarefa de teste...\n";
try {
    $task = \App\Models\Task::create([
        'user_id' => $adminUser->id,
        'description' => 'Tarefa de teste criada manualmente',
        'status' => 'Em Planejamento',
    ]);
    echo "   ✅ Tarefa criada: ID={$task->id}, Description={$task->description}\n";
} catch (\Exception $e) {
    echo "   ❌ Erro: {$e->getMessage()}\n";
}
echo "\n";

echo "=== Teste Concluído ===\n";

