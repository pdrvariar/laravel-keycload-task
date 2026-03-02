<?php
// Teste simples de atualização de tarefa via API

require 'laravel/vendor/autoload.php';
$app = require_once 'laravel/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Iniciar a aplicação
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Task;

// Buscar primeira tarefa
$task = Task::first();

if ($task) {
    echo "=== Tarefa Encontrada ===" . PHP_EOL;
    echo "ID: " . $task->id . PHP_EOL;
    echo "Título: " . $task->title . PHP_EOL;
    echo "Descrição: " . $task->description . PHP_EOL;
    echo "Status Atual: " . $task->status . PHP_EOL;

    // Tentar atualizar
    $newStatus = $task->status === 'Concluído' ? 'Em Andamento' : 'Concluído';
    echo "\nTentando atualizar para: " . $newStatus . PHP_EOL;

    $updated = $task->update([
        'title' => $task->title,
        'description' => $task->description,
        'status' => $newStatus
    ]);

    if ($updated) {
        echo "✓ Atualização bem-sucedida!" . PHP_EOL;
        $task->refresh();
        echo "Novo Status: " . $task->status . PHP_EOL;
        echo "Updated At: " . $task->updated_at . PHP_EOL;
    } else {
        echo "✗ Falha na atualização" . PHP_EOL;
    }
} else {
    echo "Nenhuma tarefa encontrada" . PHP_EOL;
}
?>

