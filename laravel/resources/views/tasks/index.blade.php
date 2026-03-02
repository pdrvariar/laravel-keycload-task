@extends('layouts.app')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header da Página -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h2 style="font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0;">
                    <i class="bi bi-list-check"></i> Minhas Tarefas
                </h2>
                <p style="color: #64748b; margin-top: 0.5rem; font-size: 1rem;">
                    Gerencie todas as suas tarefas em um único lugar
                </p>
            </div>
            <a href="{{ route('tasks.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 20px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <i class="bi bi-plus-circle"></i> Nova Tarefa
            </a>
        </div>

        <!-- Filtros e Busca -->
        <div class="card-modern" style="margin-bottom: 2rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <!-- Filtro por Status -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.95rem;">
                        Filtrar por Status
                    </label>
                    <select id="filterStatus" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease;" onchange="loadTasks()">
                        <option value="">-- Todos os Status --</option>
                        <option value="Em Planejamento">Em Planejamento</option>
                        <option value="Em Andamento">Em Andamento</option>
                        <option value="Concluído">Concluído</option>
                        <option value="Pausado">Pausado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>

                <!-- Ordenação -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.95rem;">
                        Ordenar por
                    </label>
                    <select id="sortBy" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease;" onchange="loadTasks()">
                        <option value="created_at">Data (Mais Recentes)</option>
                        <option value="created_at" data-order="asc">Data (Mais Antigos)</option>
                        <option value="description">Descrição (A-Z)</option>
                    </select>
                </div>

                <!-- Busca -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.95rem;">
                        Buscar Tarefa
                    </label>
                    <input type="text" id="searchTask" placeholder="Digite para buscar..." style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;" onkeyup="loadTasks()">
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="dashboard-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 2rem;">
            <div class="card-modern" style="padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; margin: 0; font-size: 0.9rem;">Total</p>
                        <p class="card-value" id="stat-total" style="margin: 0.5rem 0;">0</p>
                    </div>
                    <div class="card-icon primary" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="bi bi-list-check"></i>
                    </div>
                </div>
            </div>

            <div class="card-modern" style="padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; margin: 0; font-size: 0.9rem;">Em Andamento</p>
                        <p class="card-value" id="stat-progress" style="margin: 0.5rem 0;">0</p>
                    </div>
                    <div class="card-icon warning" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>

            <div class="card-modern" style="padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; margin: 0; font-size: 0.9rem;">Concluídas</p>
                        <p class="card-value" id="stat-completed" style="margin: 0.5rem 0;">0</p>
                    </div>
                    <div class="card-icon success" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="card-modern" style="padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; margin: 0; font-size: 0.9rem;">Pendentes</p>
                        <p class="card-value" id="stat-pending" style="margin: 0.5rem 0;">0</p>
                    </div>
                    <div class="card-icon danger" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Tarefas -->
        <div id="tasksContainer" class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-center py-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-2 text-muted">Carregando tarefas...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar/Visualizar Tarefa -->
<div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="taskModalTitle">Editar Tarefa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título</label>
                        <input type="text" class="form-control" id="taskTitle" placeholder="Título da tarefa" maxlength="255">
                        <small class="text-muted">Opcional - Máximo 255 caracteres</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição</label>
                        <textarea class="form-control" id="taskDescription" rows="4" placeholder="Descrição da tarefa"></textarea>
                        <small class="text-muted">Máximo 1000 caracteres</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select class="form-select" id="taskStatus">
                            <option value="Em Planejamento">Em Planejamento</option>
                            <option value="Em Andamento">Em Andamento</option>
                            <option value="Concluído">Concluído</option>
                            <option value="Pausado">Pausado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div id="formError" class="alert alert-danger d-none" role="alert"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveTaskBtn">
                    <i class="bi bi-check-circle"></i> Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmar Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja deletar esta tarefa? Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="bi bi-trash"></i> Deletar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .task-card {
        transition: all 0.3s ease;
        border-left: 5px solid #007bff;
    }

    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .task-card.status-em-planejamento {
        border-left-color: #6c757d;
    }

    .task-card.status-em-andamento {
        border-left-color: #0d6efd;
    }

    .task-card.status-concluido {
        border-left-color: #198754;
    }

    .task-card.status-pausado {
        border-left-color: #ffc107;
    }

    .task-card.status-cancelado {
        border-left-color: #dc3545;
    }

    .status-badge {
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
    }

    .status-badge.em-planejamento {
        background-color: #e2e3e5;
        color: #383d41;
    }

    .status-badge.em-andamento {
        background-color: #cfe2ff;
        color: #084298;
    }

    .status-badge.concluido {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .status-badge.pausado {
        background-color: #fff3cd;
        color: #664d03;
    }

    .status-badge.cancelado {
        background-color: #f8d7da;
        color: #842029;
    }

    .btn-action {
        padding: 0.25rem 0.5rem;
        font-size: 0.85rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
</style>

<script>
    const API_URL = '/api/tasks';
    const apiToken = document.querySelector('meta[name="api-token"]')?.content;
    let currentTaskId = null;
    let taskModal, deleteModal;

    document.addEventListener('DOMContentLoaded', function() {
        console.group('🔍 DIAGNÓSTICO - Inicialização da Página de Tarefas');
        console.log('1. Verificando elementos do DOM...');
        console.log('   - taskModal element:', document.getElementById('taskModal') ? '✓' : '✗');
        console.log('   - deleteModal element:', document.getElementById('deleteModal') ? '✓' : '✗');
        console.log('   - tasksContainer:', document.getElementById('tasksContainer') ? '✓' : '✗');

        // Esperar Bootstrap estar disponível
        if (typeof bootstrap === 'undefined') {
            console.error('❌ Bootstrap não está carregado! Aguardando...');
            setTimeout(() => location.reload(), 1000);
            return;
        }

        console.log('   - Bootstrap: ✓ (carregado)');

        taskModal = new bootstrap.Modal(document.getElementById('taskModal'));
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        console.log('2. Verificando tokens...');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        console.log('   - CSRF Token:', csrfToken ? `${csrfToken.substring(0, 20)}... (${csrfToken.length} chars)` : '✗ NÃO ENCONTRADO');
        console.log('   - API Token:', apiToken ? `${apiToken.substring(0, 20)}... (${apiToken.length} chars)` : '✗ NÃO ENCONTRADO ou VAZIO');

        if (!apiToken || apiToken.trim() === '') {
            console.error('❌ API Token não encontrado ou vazio!');
            console.error('   Você precisa fazer LOGIN primeiro!');
            console.error('   Redirecionando para /login em 3 segundos...');
            console.groupEnd();

            showError('⚠️ Você não está autenticado. Redirecionando para login...');
            setTimeout(() => {
                window.location.href = '/login';
            }, 3000);
            return;
        }

        console.log('3. Verificando parâmetros da URL...');
        const urlParams = new URLSearchParams(window.location.search);
        const filterParam = urlParams.get('filter');

        if (filterParam) {
            console.log('   ✓ Filtro encontrado na URL:', filterParam);
            // Aplicar filtro automaticamente
            const filterSelect = document.getElementById('filterStatus');
            if (filterSelect) {
                filterSelect.value = filterParam;
                console.log('   ✓ Filtro aplicado ao select');
            }
        } else {
            console.log('   - Nenhum filtro na URL');
        }

        console.log('4. Iniciando carregamento de tarefas...');
        console.groupEnd();
        loadTasks();

        // Event listeners para filtros
        document.getElementById('filterStatus').addEventListener('change', loadTasks);
        document.getElementById('sortBy').addEventListener('change', loadTasks);

        // Event listeners para modal
        document.getElementById('saveTaskBtn').addEventListener('click', saveTask);
        document.getElementById('confirmDeleteBtn').addEventListener('click', deleteTask);
    });

    function getAuthHeaders() {
        return {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Authorization': `Bearer ${apiToken}`
        };
    }

    async function handleApiResponse(response) {
        const isJson = response.headers.get('content-type')?.includes('application/json');
        const data = isJson ? await response.json() : null;

        if (!response.ok) {
            if (response.status === 401) {
                // Token expirado ou inválido - redirecionar para login
                showError('Sessão expirada. Redirecionando para login...');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
                throw new Error('Sessão expirada. Faça login novamente.');
            }
            if (!isJson) {
                throw new Error('Erro no servidor (Resposta não-JSON). Verifique se você está logado.');
            }
            throw new Error(data.message || 'Ocorreu um erro desconhecido.');
        }
        return data;
    }

    function loadTasks() {
        console.group('📋 CARREGANDO TAREFAS');

        const status = document.getElementById('filterStatus').value;
        const sortBy = document.getElementById('sortBy').value;

        let params = new URLSearchParams();
        if (status) params.append('status', status);

        // Converter sortBy para os parâmetros esperados
        if (sortBy === 'created_at') {
            params.append('sort_by', 'created_at');
            params.append('sort_order', 'desc');
        } else if (sortBy === 'created_at_asc') {
            params.append('sort_by', 'created_at');
            params.append('sort_order', 'asc');
        } else if (sortBy === 'description') {
            params.append('sort_by', 'description');
            params.append('sort_order', 'asc');
        }

        const url = `${API_URL}?${params}`;
        const headers = getAuthHeaders();

        console.log('📤 Fazendo requisição para:', url);
        console.log('📤 Headers:', headers);

        fetch(url, { headers: headers })
            .then(response => {
                console.log('📥 Resposta:', {
                    status: response.status,
                    statusText: response.statusText,
                    ok: response.ok,
                    contentType: response.headers.get('content-type')
                });
                return handleApiResponse(response);
            })
            .then(data => {
                console.log('✅ Dados processados:', data);
                console.groupEnd();

                if (data.success) {
                    console.log(`📊 Renderizando ${data.data ? data.data.length : 0} tarefa(s)`);
                    renderTasks(data.data);
                } else {
                    console.error('❌ API retornou erro:', data.message);
                    showError('Erro ao carregar tarefas: ' + (data.message || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('💥 ERRO:', error);
                console.groupEnd();
                showError(error.message || 'Erro ao carregar tarefas');
            });
    }

    function updateStats(tasks) {
        // Calcular estatísticas
        const total = tasks.length;
        const emAndamento = tasks.filter(t => t.status === 'Em Andamento').length;
        const concluidas = tasks.filter(t => t.status === 'Concluído').length;
        const pendentes = tasks.filter(t => t.status === 'Em Planejamento').length;

        // Atualizar elementos no DOM
        document.getElementById('stat-total').textContent = total;
        document.getElementById('stat-progress').textContent = emAndamento;
        document.getElementById('stat-completed').textContent = concluidas;
        document.getElementById('stat-pending').textContent = pendentes;
    }

    function renderTasks(tasks) {
        const container = document.getElementById('tasksContainer');

        // Atualizar estatísticas
        updateStats(tasks);

        if (tasks.length === 0) {
            container.innerHTML = `
                <div class="card border-0 shadow-sm">
                    <div class="card-body empty-state">
                        <div class="empty-state-icon">📋</div>
                        <h5 class="text-muted">Nenhuma tarefa encontrada</h5>
                        <p class="text-muted mb-0">Crie sua primeira tarefa para começar</p>
                    </div>
                </div>
            `;
            return;
        }

        let html = '<div class="row g-3">';

        tasks.forEach(task => {
            const statusClass = getStatusClass(task.status);
            const statusBadgeClass = getStatusBadgeClass(task.status);
            const createdDate = new Date(task.created_at).toLocaleDateString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            html += `
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm task-card ${statusClass} h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="status-badge ${statusBadgeClass}">${task.status}</span>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary btn-action" onclick="editTask(${task.id})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-action" onclick="confirmDelete(${task.id})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <h5 class="card-title mb-2" style="color: #1e293b; font-weight: 700;">
                                <i class="bi bi-bookmark"></i> ${escapeHtml(task.title || '(SEM TITULO)')}
                            </h5>
                            <p class="card-text text-wrap mb-3 flex-grow-1" style="word-break: break-word;">
                                ${escapeHtml(task.description)}
                            </p>
                            <small class="text-muted">
                                <i class="bi bi-calendar-event"></i> ${createdDate}
                            </small>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        container.innerHTML = html;
    }

    function editTask(taskId) {
        currentTaskId = taskId;
        fetch(`${API_URL}/${taskId}`, {
            headers: getAuthHeaders()
        })
            .then(handleApiResponse)
            .then(data => {
                if (data.success) {
                    const task = data.data;
                    document.getElementById('taskTitle').value = task.title || '(SEM TITULO)';
                    document.getElementById('taskDescription').value = task.description;
                    document.getElementById('taskStatus').value = task.status;
                    document.getElementById('taskModalTitle').textContent = 'Editar Tarefa';
                    taskModal.show();
                } else {
                    showError('Erro ao carregar tarefa');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showError('Erro ao carregar tarefa');
            });
    }

    function saveTask() {
        const title = document.getElementById('taskTitle').value.trim();
        const description = document.getElementById('taskDescription').value.trim();
        const status = document.getElementById('taskStatus').value;

        if (!title) {
            showFormError('Por favor, informe um título');
            return;
        }

        if (!description) {
            showFormError('Por favor, informe uma descrição');
            return;
        }

        const method = currentTaskId ? 'PUT' : 'POST';
        const url = currentTaskId ? `${API_URL}/${currentTaskId}` : API_URL;

        fetch(url, {
            method: method,
            headers: getAuthHeaders(),
            body: JSON.stringify({
                title: title || '(SEM TITULO)',
                description: description,
                status: status
            })
        })
            .then(handleApiResponse)
            .then(data => {
                if (data.success) {
                    taskModal.hide();
                    loadTasks();
                    showSuccess(data.message);
                } else {
                    showFormError(data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showFormError('Erro ao salvar tarefa');
            });
    }

    function confirmDelete(taskId) {
        currentTaskId = taskId;
        deleteModal.show();
    }

    function deleteTask() {
        if (!currentTaskId) return;

        fetch(`${API_URL}/${currentTaskId}`, {
            method: 'DELETE',
            headers: getAuthHeaders()
        })
            .then(handleApiResponse)
            .then(data => {
                if (data.success) {
                    deleteModal.hide();
                    loadTasks();
                    showSuccess(data.message);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showError('Erro ao deletar tarefa');
            });
    }

    function getStatusClass(status) {
        const map = {
            'Em Planejamento': 'status-em-planejamento',
            'Em Andamento': 'status-em-andamento',
            'Concluído': 'status-concluido',
            'Pausado': 'status-pausado',
            'Cancelado': 'status-cancelado'
        };
        return map[status] || '';
    }

    function getStatusBadgeClass(status) {
        const map = {
            'Em Planejamento': 'em-planejamento',
            'Em Andamento': 'em-andamento',
            'Concluído': 'concluido',
            'Pausado': 'pausado',
            'Cancelado': 'cancelado'
        };
        return map[status] || '';
    }

    function showError(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show';
        alert.innerHTML = `
            <i class="bi bi-exclamation-circle"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alert, container.firstChild);
        setTimeout(() => alert.remove(), 5000);
    }

    function showSuccess(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show';
        alert.innerHTML = `
            <i class="bi bi-check-circle"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alert, container.firstChild);
        setTimeout(() => alert.remove(), 5000);
    }

    function showFormError(message) {
        const errorDiv = document.getElementById('formError');
        errorDiv.textContent = message;
        errorDiv.classList.remove('d-none');
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
</script>

@endsection
