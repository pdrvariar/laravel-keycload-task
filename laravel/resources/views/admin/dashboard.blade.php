@extends('layouts.app')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header da Página -->
        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0;">
                Painel Administrativo 🛡️
            </h2>
            <p style="color: #64748b; margin-top: 0.5rem; font-size: 1rem;">
                Visão geral do sistema e gerenciamento de usuários
            </p>
        </div>

        <!-- Grid de Cards Estratégicos -->
        <div class="dashboard-grid">
            <!-- Card: Total de Usuários -->
            <div class="card-modern">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Usuários</h3>
                        <p class="card-label">Total de usuários registrados</p>
                    </div>
                    <div class="card-icon primary">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <p class="card-value" id="total-users">0</p>
                <div class="card-footer">
                    <i class="bi bi-person-plus"></i> Gerenciar usuários
                </div>
            </div>

            <!-- Card: Total de Tarefas no Sistema -->
            <div class="card-modern">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Total de Tarefas</h3>
                        <p class="card-label">Tarefas em todo o sistema</p>
                    </div>
                    <div class="card-icon warning">
                        <i class="bi bi-layers"></i>
                    </div>
                </div>
                <p class="card-value" id="total-system-tasks">0</p>
                <div class="card-footer">
                    <i class="bi bi-eye"></i> Visualizar todas
                </div>
            </div>

            <!-- Card: Status do Keycloak -->
            <div class="card-modern">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Keycloak</h3>
                        <p class="card-label">Status do serviço de identidade</p>
                    </div>
                    <div class="card-icon success">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <p class="card-value" style="font-size: 1.5rem; color: #10b981;">ONLINE</p>
                <div class="card-footer">
                    <i class="bi bi-check-circle"></i> Conexão estabelecida
                </div>
            </div>
        </div>

        <!-- Seção de Ações Rápidas -->
        <div class="card-modern" style="margin-top: 2rem;">
            <h3 style="font-size: 1.3rem; font-weight: 700; color: #1e293b; margin-bottom: 1.5rem;">
                <i class="bi bi-lightning-charge"></i> Ações Rápidas
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="{{ route('admin.tasks.index') }}" style="text-decoration: none; color: inherit;">
                    <div style="padding: 1.5rem; border: 1px solid #e2e8f0; border-radius: 12px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">
                        <i class="bi bi-list-ul" style="font-size: 2rem; color: #6366f1; margin-bottom: 1rem; display: block;"></i>
                        <h4 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">Gerenciar Tarefas</h4>
                        <p style="font-size: 0.9rem; color: #64748b;">Ver e editar todas as tarefas</p>
                    </div>
                </a>

                <a href="#" onclick="alert('Funcionalidade em desenvolvimento')" style="text-decoration: none; color: inherit;">
                    <div style="padding: 1.5rem; border: 1px solid #e2e8f0; border-radius: 12px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">
                        <i class="bi bi-people" style="font-size: 2rem; color: #ec4899; margin-bottom: 1rem; display: block;"></i>
                        <h4 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">Gerenciar Usuários</h4>
                        <p style="font-size: 0.9rem; color: #64748b;">Ver lista de usuários</p>
                    </div>
                </a>

                <a href="http://localhost:8080" target="_blank" style="text-decoration: none; color: inherit;">
                    <div style="padding: 1.5rem; border: 1px solid #e2e8f0; border-radius: 12px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">
                        <i class="bi bi-box-arrow-up-right" style="font-size: 2rem; color: #f59e0b; margin-bottom: 1rem; display: block;"></i>
                        <h4 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">Console Keycloak</h4>
                        <p style="font-size: 0.9rem; color: #64748b;">Acessar painel do Keycloak</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Carregar estatísticas via API
        async function loadAdminStats() {
            try {
                const token = document.querySelector('meta[name="api-token"]')?.content;

                if (!token) return;

                // Carregar tarefas (como admin vê todas)
                const tasksResponse = await fetch('/api/tasks', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (tasksResponse.ok) {
                    const tasksData = await tasksResponse.json();
                    document.getElementById('total-system-tasks').textContent = tasksData.data.length;
                }

                // Carregar usuários (endpoint específico para admin)
                const usersResponse = await fetch('/api/users', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (usersResponse.ok) {
                    const usersData = await usersResponse.json();
                    document.getElementById('total-users').textContent = usersData.data.length;
                } else {
                    // Fallback se endpoint não existir
                    document.getElementById('total-users').textContent = '-';
                }

            } catch (error) {
                console.error('Erro ao carregar estatísticas:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', loadAdminStats);
    </script>
@endsection
