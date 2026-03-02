<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rotas protegidas pelo guard 'api' (Keycloak)
Route::middleware(['auth:api', 'validate.keycloak.token'])->group(function () {
    // CRUD de Tarefas - MINHAS TAREFAS (sempre do usuário logado, mesmo para admins)
    Route::get('/tasks', [TaskController::class, 'myTasks']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    // Rotas Admin - TODAS AS TAREFAS (apenas para admins)
    Route::get('/admin/tasks', [TaskController::class, 'index']);
    Route::get('/admin/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/admin/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/admin/tasks/{task}', [TaskController::class, 'destroy']);
    Route::get('/admin/users', [TaskController::class, 'users']);

    // Alias para backward compatibility
    Route::get('/users', [TaskController::class, 'users']);
});
