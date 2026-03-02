<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Recupera o usuário local (Eloquent) baseado no token autenticado.
     * Se o usuário não existir localmente, tenta criá-lo ou retorna erro.
     */
    private function getLocalUser(Request $request): ?User
    {
        $user = $request->user();

        if (!$user) {
            return null;
        }

        // Se já for uma instância de User (Eloquent), retorna
        if ($user instanceof User) {
            return $user;
        }

        // Se for um objeto genérico do Keycloak (dependendo da lib usada), tentamos buscar pelo email
        $email = $user->email ?? $user->getAttribute('email') ?? null;

        if (!$email && method_exists($user, 'toArray')) {
            $userData = $user->toArray();
            $email = $userData['email'] ?? null;
        }

        if ($email) {
            // Busca ou cria o usuário localmente para garantir que temos um ID para a FK
            return User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $user->name ?? $user->getAttribute('name') ?? 'Keycloak User',
                    'password' => bcrypt(str()->random(16)), // Senha aleatória, auth é via Keycloak
                ]
            );
        }

        return null;
    }

    /**
     * Extrai as informações de permissão do usuário autenticado via token.
     */
    private function getUserAndRoles(Request $request): array
    {
        $user = $this->getLocalUser($request);

        if (!$user) {
            return [null, false];
        }

        // Tenta obter roles do token JWT decodificado
        // A forma de acesso ao token depende da biblioteca Keycloak usada.
        // Vamos tentar acessar via Auth::token() ou propriedades do request se disponíveis.
        $roles = [];

        // Tentativa 1: Via método token() no user (comum em algumas libs)
        if (method_exists($request->user(), 'token')) {
            $token = $request->user()->token;
            $clientRoles = data_get((array)$token, 'resource_access.task-controller.roles', []);
            $realmRoles = data_get((array)$token, 'realm_access.roles', []);
            $roles = array_merge($clientRoles, $realmRoles);
        }
        // Tentativa 2: Decodificando o token do header (fallback manual)
        elseif ($bearerToken = $request->bearerToken()) {
            try {
                $tokenParts = explode('.', $bearerToken);
                if (count($tokenParts) === 3) {
                    $payload = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', $tokenParts[1]))), true);
                    $clientRoles = data_get($payload, 'resource_access.task-controller.roles', []);
                    $realmRoles = data_get($payload, 'realm_access.roles', []);
                    $roles = array_merge($clientRoles, $realmRoles);
                }
            } catch (\Exception $e) {
                // Ignora erro de decode
            }
        }

        $isAdmin = in_array('admin', $roles);

        return [$user, $isAdmin];
    }

    /**
     * Listar tarefas
     */
    public function index(Request $request): JsonResponse
    {
        try {
            [$user, $isAdmin] = $this->getUserAndRoles($request);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Usuário não autenticado ou não encontrado no banco local.'], 401);
            }

            $query = Task::with('user');

            if (!$isAdmin) {
                $query->where('user_id', $user->id);
            } elseif ($request->has('user_id')) {
                $query->where('user_id', $request->input('user_id'));
            }

            if ($request->has('status') && $request->input('status') !== '') {
                $query->where('status', $request->input('status'));
            }

            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            return response()->json([
                'success' => true,
                'data' => $query->get(),
                'is_admin' => $isAdmin,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao listar tarefas: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao carregar tarefas: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Armazenar nova tarefa
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = $this->getLocalUser($request);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Usuário não autenticado ou não encontrado no banco local.'], 401);
            }

            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'required|string|max:1000',
                'status' => 'nullable|in:Em Planejamento,Em Andamento,Concluído,Pausado,Cancelado',
            ]);

            $task = Task::create([
                'user_id' => $user->id,
                'title' => $validated['title'] ?? '(SEM TITULO)',
                'description' => $validated['description'],
                'status' => $validated['status'] ?? 'Em Planejamento',
            ]);

            return response()->json(['success' => true, 'message' => 'Tarefa criada com sucesso!', 'data' => $task], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Erro de validação', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao criar tarefa: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao criar tarefa: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mostrar uma tarefa específica
     */
    public function show(Request $request, Task $task): JsonResponse
    {
        try {
            [$user, $isAdmin] = $this->getUserAndRoles($request);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Não autenticado.'], 401);
            }

            if (!$isAdmin && $task->user_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Acesso não autorizado.'], 403);
            }

            return response()->json(['success' => true, 'data' => $task->load('user')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao carregar tarefa: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Atualizar tarefa
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        try {
            [$user, $isAdmin] = $this->getUserAndRoles($request);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Não autenticado.'], 401);
            }

            if (!$isAdmin && $task->user_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Acesso não autorizado.'], 403);
            }

            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'required|string|max:1000',
                'status' => 'required|in:Em Planejamento,Em Andamento,Concluído,Pausado,Cancelado',
            ]);

            $task->update($validated);

            return response()->json(['success' => true, 'message' => 'Tarefa atualizada com sucesso!', 'data' => $task]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Erro de validação', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar tarefa: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Deletar tarefa
     */
    public function destroy(Request $request, Task $task): JsonResponse
    {
        try {
            [$user, $isAdmin] = $this->getUserAndRoles($request);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Não autenticado.'], 401);
            }

            if (!$isAdmin && $task->user_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Acesso não autorizado.'], 403);
            }

            $task->delete();

            return response()->json(['success' => true, 'message' => 'Tarefa deletada com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao deletar tarefa: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Listar usuários (apenas para admins)
     */
    public function users(Request $request): JsonResponse
    {
        try {
            [$user, $isAdmin] = $this->getUserAndRoles($request);

            if (!$isAdmin) {
                return response()->json(['success' => false, 'message' => 'Acesso não autorizado.'], 403);
            }

            $users = User::all(['id', 'name', 'email']);

            return response()->json(['success' => true, 'data' => $users]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao listar usuários: ' . $e->getMessage()], 500);
        }
    }
}
