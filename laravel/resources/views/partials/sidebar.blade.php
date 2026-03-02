<!-- Sidebar Menu -->
<aside class="sidebar">
    <nav>
        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li>
                <?php
                    $clientId = config('keycloak.client_id', 'task-app');
                    $keycloakUser = session('keycloak_user', []);
                    $clientRoles = $keycloakUser['resource_access'][$clientId]['roles'] ?? [];
                    $realmRoles = $keycloakUser['realm_access']['roles'] ?? [];
                    $userRoles = array_merge($clientRoles, $realmRoles);
                ?>
                <a href="{{ auth()->check() && in_array('admin', $userRoles) ? route('admin.dashboard') : route('dashboard') }}"
                   class="@if(request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')) active @endif">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Tarefas -->
            <li>
                <a href="{{ route('tasks.index') }}"
                   class="@if(request()->routeIs('tasks.*')) active @endif">
                    <i class="bi bi-list-check"></i>
                    <span>Tarefas</span>
                </a>
            </li>

            <!-- Nova Tarefa -->
            <li>
                <a href="{{ route('tasks.create') }}"
                   class="@if(request()->routeIs('tasks.create')) active @endif">
                    <i class="bi bi-plus-circle"></i>
                    <span>Nova Tarefa</span>
                </a>
            </li>

            <!-- Admin Menu (apenas para admins) -->
            @if(in_array('admin', $userRoles))
                <li style="margin-top: 2rem; padding: 0 1.5rem; color: #94a3b8; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">
                    ADMINISTRAÇÃO
                </li>

                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="@if(request()->routeIs('admin.dashboard')) active @endif">
                        <i class="bi bi-shield-check"></i>
                        <span>Painel Admin</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.tasks.index') }}"
                       class="@if(request()->routeIs('admin.tasks.*')) active @endif">
                        <i class="bi bi-list-ul"></i>
                        <span>Todas as Tarefas</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</aside>
