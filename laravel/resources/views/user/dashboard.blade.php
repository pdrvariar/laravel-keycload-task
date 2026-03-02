@extends('layouts.app')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header da Página -->
        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0;">
                Bem-vindo, {{ session('keycloak_user.name') ?? 'Usuário' }}! 👋
            </h2>
            <p style="color: #64748b; margin-top: 0.5rem; font-size: 1rem;">
                <i class="bi bi-calendar-event"></i>
                {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
            </p>
        </div>

        <!-- Grid de Cards Estratégicos -->
        <div class="dashboard-grid">
            <!-- Card: Total de Tarefas -->
            <div class="card-modern" style="cursor: pointer; transition: all 0.3s ease;" onclick="goToTasks('')" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Minhas Tarefas</h3>
                        <p class="card-label">Total de tarefas criadas</p>
                    </div>
                    <div class="card-icon primary">
                        <i class="bi bi-list-check"></i>
                    </div>
                </div>
                <p class="card-value" id="total-tasks">0</p>
                <div class="card-footer">
                    <i class="bi bi-arrow-up"></i> Acompanhe seu progresso
                </div>
            </div>

            <!-- Card: Tarefas em Andamento -->
            <div class="card-modern" style="cursor: pointer; transition: all 0.3s ease;" onclick="goToTasks('Em Andamento')" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Em Andamento</h3>
                        <p class="card-label">Tarefas ativas hoje</p>
                    </div>
                    <div class="card-icon warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <p class="card-value" id="in-progress-tasks">0</p>
                <div class="card-footer">
                    <i class="bi bi-fire"></i> Mantenha o foco!
                </div>
            </div>

            <!-- Card: Tarefas Concluídas -->
            <div class="card-modern" style="cursor: pointer; transition: all 0.3s ease;" onclick="goToTasks('Concluído')" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Concluídas</h3>
                        <p class="card-label">Parabéns pelos sucessos!</p>
                    </div>
                    <div class="card-icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
                <p class="card-value" id="completed-tasks">0</p>
                <div class="card-footer">
                    <i class="bi bi-star-fill"></i> Excelente desempenho
                </div>
            </div>

            <!-- Card: Taxa de Conclusão -->
            <div class="card-modern">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Taxa de Conclusão</h3>
                        <p class="card-label">Percentual de tarefas concluídas</p>
                    </div>
                    <div class="card-icon primary">
                        <i class="bi bi-percent"></i>
                    </div>
                </div>
                <p class="card-value"><span id="completion-rate">0</span>%</p>
                <div style="margin-top: 1rem;">
                    <div style="background: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden;">
                        <div id="progress-bar" style="background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); height: 100%; width: 0%; transition: width 0.3s ease;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Tarefas do Dia -->
        <div class="card-modern" style="margin-top: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="font-size: 1.3rem; font-weight: 700; color: #1e293b; margin: 0;">
                        <i class="bi bi-calendar-check"></i> Tarefas do Dia
                    </h3>
                    <p style="color: #64748b; margin-top: 0.5rem; font-size: 0.95rem;">
                        Gerencie suas tarefas para hoje
                    </p>
                </div>
                <a href="{{ route('tasks.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 20px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="bi bi-plus-circle"></i> Nova Tarefa
                </a>
            </div>

            <!-- Lista de Tarefas -->
            <ul class="task-list" id="tasks-list">
                <li style="text-align: center; padding: 2rem; color: #94a3b8;">
                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                    <p>Carregando tarefas...</p>
                </li>
            </ul>
        </div>

        <!-- Seção de Dicas e Melhorias -->
        <div class="card-modern" style="margin-top: 2rem; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-left: 4px solid #667eea;">
            <div style="display: flex; gap: 1rem;">
                <div style="font-size: 2rem;">💡</div>
                <div>
                    <h4 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0;">Dica do Dia</h4>
                    <p style="color: #64748b; margin-top: 0.5rem;">
                        Focalize nas tarefas mais importantes primeiro. A produtividade aumenta quando você prioriza o que realmente importa!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variável para controlar múltiplos cliques simultâneos
        let isUpdatingTask = false;

        // Carregar dados das tarefas via API
        async function loadTasks() {
            try {
                const token = document.querySelector('meta[name="api-token"]')?.content;

                // Check if token is missing or invalid
                if (!token || token === 'null' || token === '') {
                    console.error('Token não encontrado. Redirecionando para login...');
                    window.location.href = '/login';
                    return;
                }

                const response = await fetch('/api/tasks', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    if (response.status === 401) {
                        console.error('Token expirado ou inválido. Redirecionando para login...');
                        window.location.href = '/login';
                        return;
                    }
                    throw new Error(`Failed to load tasks: ${response.status} ${response.statusText}`);
                }

                const data = await response.json();
                const tasks = data.data || [];

                // Calcular estatísticas
                const totalTasks = tasks.length;
                const completedTasks = tasks.filter(t => t.status === 'Concluído').length;
                const inProgressTasks = tasks.filter(t => t.status === 'Em Andamento').length;
                const completionRate = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;

                // Atualizar cards
                document.getElementById('total-tasks').textContent = totalTasks;
                document.getElementById('completed-tasks').textContent = completedTasks;
                document.getElementById('in-progress-tasks').textContent = inProgressTasks;
                document.getElementById('completion-rate').textContent = completionRate;
                document.getElementById('progress-bar').style.width = completionRate + '%';

                // Renderizar tarefas
                renderTasks(tasks);
            } catch (error) {
                console.error('Erro ao carregar tarefas:', error);
                const errorMessage = error.message || 'Erro desconhecido ao carregar tarefas';
                document.getElementById('tasks-list').innerHTML = `
                    <li style="text-align: center; padding: 2rem; color: #ef4444;">
                        <i class="bi bi-exclamation-circle" style="font-size: 2rem; display: block; margin-bottom: 1rem;"></i>
                        <p style="margin: 0.5rem 0; font-weight: 600;">Erro ao carregar tarefas</p>
                        <p style="margin: 0.5rem 0; font-size: 0.9rem; color: #94a3b8;">${errorMessage}</p>
                        <button onclick="loadTasks()" style="margin-top: 1rem; background: #667eea; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-weight: 600;">
                            <i class="bi bi-arrow-clockwise"></i> Tentar novamente
                        </button>
                    </li>
                `;
            }
        }

        function getStatusClass(status) {
            const statusMap = {
                'Em Planejamento': 'planning',
                'Em Andamento': 'in-progress',
                'Concluído': 'done',
                'Pausado': 'paused',
                'Cancelado': 'cancelled'
            };
            return statusMap[status] || 'planning';
        }

        function renderTasks(tasks) {
            const tasksList = document.getElementById('tasks-list');

            if (tasks.length === 0) {
                tasksList.innerHTML = '<li style="text-align: center; padding: 2rem; color: #94a3b8;"><i class="bi bi-inbox"></i> <p>Nenhuma tarefa criada ainda</p></li>';
                return;
            }

            tasksList.innerHTML = tasks.map(task => `
                <li class="task-item">
                    <div class="task-checkbox ${task.status === 'Concluído' ? 'checked' : ''}" onclick="toggleTaskStatus(${task.id})" style="cursor: pointer;" id="checkbox-${task.id}">
                        ${task.status === 'Concluído' ? '<i class="bi bi-check"></i>' : ''}
                    </div>
                    <div class="task-content">
                        <p class="task-title" style="${task.status === 'Concluído' ? 'text-decoration: line-through; opacity: 0.6;' : ''}">${task.title}</p>
                        <span class="task-status ${getStatusClass(task.status)}">${task.status}</span>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="/tasks/${task.id}/edit" style="color: #667eea; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </li>
            `).join('');
        }

        async function toggleTaskStatus(taskId) {
            // Previnir múltiplos cliques simultâneos
            if (isUpdatingTask) {
                console.warn('Já existe uma atualização em progresso. Por favor aguarde...');
                return;
            }

            isUpdatingTask = true;

            // Feedback visual - mostrar loading no checkbox
            const checkboxElement = document.getElementById(`checkbox-${taskId}`);
            if (checkboxElement) {
                checkboxElement.style.opacity = '0.5';
                checkboxElement.style.pointerEvents = 'none';
                checkboxElement.innerHTML = '<i class="bi bi-hourglass-split" style="animation: spin 1s linear infinite;"></i>';
            }

            console.log('toggleTaskStatus chamado para taskId:', taskId);

            const token = document.querySelector('meta[name="api-token"]')?.content;

            if (!token || token === 'null' || token === '') {
                console.error('Token não encontrado');
                alert('Erro: Token não encontrado. Recarregue a página.');
                isUpdatingTask = false;
                if (checkboxElement) {
                    checkboxElement.style.opacity = '1';
                    checkboxElement.style.pointerEvents = 'auto';
                }
                return;
            }

            try {
                // Primeiro, buscar os dados atuais da tarefa
                console.log('Buscando dados atuais da tarefa...');
                const getResponse = await fetch(`/api/tasks/${taskId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!getResponse.ok) {
                    throw new Error(`Erro ao buscar tarefa: ${getResponse.status}`);
                }

                const getData = await getResponse.json();
                const task = getData.data;
                console.log('Dados da tarefa:', task);

                // Determinar o novo status
                const newStatus = task.status === 'Concluído' ? 'Em Andamento' : 'Concluído';
                console.log('Status atual:', task.status, '-> Novo status:', newStatus);

                // Agora fazer o update com todos os dados
                const payload = {
                    title: task.title || '',
                    description: task.description || '',
                    status: newStatus
                };

                console.log('Enviando payload para atualizar:', payload);

                const updateResponse = await fetch(`/api/tasks/${taskId}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const updateData = await updateResponse.json();
                console.log('Resposta da atualização:', { status: updateResponse.status, data: updateData });

                if (!updateResponse.ok) {
                    console.error('Erro na atualização:', updateData);
                    throw new Error(`Erro ao atualizar tarefa: ${updateResponse.status} - ${JSON.stringify(updateData)}`);
                }

                console.log('Tarefa atualizada com sucesso! Recarregando lista...');

                // Aguardar um pouco antes de recarregar para garantir que o servidor processou
                setTimeout(() => {
                    loadTasks();
                    isUpdatingTask = false;
                }, 500);
            } catch (error) {
                console.error('Erro ao atualizar status da tarefa:', error);
                alert('Erro ao atualizar tarefa: ' + error.message);
                isUpdatingTask = false;
                // Restaurar estado visual do checkbox em caso de erro
                if (checkboxElement) {
                    checkboxElement.style.opacity = '1';
                    checkboxElement.style.pointerEvents = 'auto';
                }
            }
        }

        // Carregar tarefas quando a página carrega
        document.addEventListener('DOMContentLoaded', loadTasks);

        // Recarregar a cada 30 segundos
        setInterval(loadTasks, 30000);

        // Função para redirecionar para a página de tasks com filtro
        function goToTasks(status) {
            if (status === '') {
                // Minhas Tarefas - sem filtro
                window.location.href = '{{ route("tasks.index") }}';
            } else {
                // Com filtro de status
                window.location.href = '{{ route("tasks.index") }}?filter=' + encodeURIComponent(status);
            }
        }
    </script>
@endsection
