<!-- Header Moderno -->
<div class="modern-header">
    <div class="header-content">
        <!-- Logo e Título -->
        <?php
            $clientId = config('keycloak.client_id', 'task-app');
            $keycloakUser = session('keycloak_user', []);
            $clientRoles = $keycloakUser['resource_access'][$clientId]['roles'] ?? [];
            $realmRoles = $keycloakUser['realm_access']['roles'] ?? [];
            $allRoles = array_merge($clientRoles, $realmRoles);
        ?>
        <a href="{{ auth()->check() && in_array('admin', $allRoles) ? route('admin.dashboard') : route('dashboard') }}"
           class="header-brand" style="text-decoration: none; color: inherit; cursor: pointer;">
            <div class="header-brand-icon">
                <i class="bi bi-list-check"></i>
            </div>
            <div class="header-brand-text">
                <h1>Task Controller</h1>
                <p>Rattes Factory</p>
            </div>
        </a>

        <!-- Informações do Usuário e Logout -->
        <div class="header-user">
            <div class="user-info">
                <div class="user-avatar">
                    {{ substr(session('keycloak_user.name') ?? 'U', 0, 1) }}
                </div>
                <div class="user-details">
                    <h3>{{ session('keycloak_user.name') ?? session('keycloak_user')['name'] ?? 'Usuário' }}</h3>
                    <p>{{ session('keycloak_user.email') ?? session('keycloak_user')['email'] ?? 'usuario@example.com' }}</p>
                    <?php
                        // Debug (remover após teste)
                        \Log::info('Header Debug', [
                            'keycloak_user_exists' => !is_null($keycloakUser),
                            'client_id' => $clientId ?? 'N/A',
                            'resource_access_keys' => is_array($keycloakUser) ? array_keys($keycloakUser['resource_access'] ?? []) : [],
                            'roles_found' => $allRoles,
                            'email' => $keycloakUser['email'] ?? 'N/A'
                        ]);

                        // Verifica se é admin
                        $isAdmin = in_array('admin', $allRoles);
                        $role = $isAdmin ? 'admin' : 'user';

                        // Configuração de badge por role
                        $badgeConfig = [
                            'admin' => [
                                'text' => 'Administrador',
                                'icon' => 'bi-shield-check',
                                'bg' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                                'color' => '#ffffff'
                            ],
                            'user' => [
                                'text' => 'Usuário',
                                'icon' => 'bi-person-check',
                                'bg' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                                'color' => '#ffffff'
                            ]
                        ];

                        $config = $badgeConfig[$role] ?? $badgeConfig['user'];
                    ?>
                    <span class="user-role-badge" style="background: {{ $config['bg'] }}; color: {{ $config['color'] }};">
                        <i class="bi {{ $config['icon'] }}"></i>
                        {{ $config['text'] }}
                    </span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </button>
            </form>
        </div>
    </div>
</div>
