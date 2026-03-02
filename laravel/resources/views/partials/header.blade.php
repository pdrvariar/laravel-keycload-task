<!-- Header Moderno -->
<div class="modern-header">
    <div class="header-content">
        <!-- Logo e Título -->
        <a href="{{ auth()->check() && in_array('admin', session('keycloak_user.resource_access.task-controller.roles') ?? []) ? route('admin.dashboard') : route('dashboard') }}"
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
                    <h3>{{ session('keycloak_user.name') ?? 'Usuário' }}</h3>
                    <p>{{ session('keycloak_user.email') ?? 'usuario@example.com' }}</p>
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
