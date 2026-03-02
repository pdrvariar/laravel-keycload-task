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
    // CRUD de Tarefas
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    // Listar usuários (apenas para admins)
    Route::get('/users', [TaskController::class, 'users']);

    // Alias para admin (para manter consistência com o frontend)
    Route::get('/admin/users', [TaskController::class, 'users']);
});
