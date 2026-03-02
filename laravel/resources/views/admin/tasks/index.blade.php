@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header com Título e Botão -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-0">
                <i class="bi bi-shield-check"></i> Gerenciamento de Tarefas
            </h1>
            <small class="text-muted">Visualize e gerencie todas as tarefas do sistema</small>
        </div>
    </div>

    <!-- Filtros Avançados -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Filtrar por Usuário</label>
                    <select class="form-select form-select-sm" id="filterUser">
                        <option value="">-- Todos os Usuários --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Filtrar por Status</label>
                    <select class="form-select form-select-sm" id="filterStatus">
                        <option value="">-- Todos os Status --</option>
                        <option value="Em Planejamento">Em Planejamento</option>
                        <option value="Em Andamento">Em Andamento</option>
                        <option value="Concluído">Concluído</option>
                        <option value="Pausado">Pausado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Ordenar por</label>
                    <select class="form-select form-select-sm" id="sortBy">
                        <option value="created_at">Data (Mais Recentes)</option>
                        <option value="created_at_asc">Data (Mais Antigos)</option>
                        <option value="description">Descrição (A-Z)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row g-3 mb-4" id="statsContainer">
        <!-- Carregado via JavaScript -->
    </div>

    <!-- Tabela de Tarefas -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div id="tasksTableContainer">
                <!-- Carregado via JavaScript -->
                <div class="text-center p-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-2">Carregando tarefas...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Visualizar Tarefa -->
<div class="modal fade" id="viewTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewTaskTitle">Detalhes da Tarefa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label text-muted small">Descrição</label>
                        <p class="text-wrap" id="viewTaskDescription"></p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Status</label>
                        <div id="viewTaskStatus"></div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Usuário</label>
                        <p id="viewTaskUser"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Email</label>
                        <p id="viewTaskEmail"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Criado em</label>
                        <p id="viewTaskCreated"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Atualizado em</label>
                        <p id="viewTaskUpdated"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id="editFromViewBtn">
                    <i class="bi bi-pencil"></i> Editar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Tarefa (Admin) -->
<div class="modal fade" id="editTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Editar Tarefa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição</label>
                        <textarea class="form-control" id="editTaskDescription" rows="4" maxlength="1000"></textarea>
                        <small class="text-muted">Máximo 1000 caracteres</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select class="form-select" id="editTaskStatus">
                            <option value="Em Planejamento">Em Planejamento</option>
                            <option value="Em Andamento">Em Andamento</option>
                            <option value="Concluído">Concluído</option>
                            <option value="Pausado">Pausado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div id="editFormError" class="alert alert-danger d-none" role="alert"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveEditTaskBtn">
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
    .table-hover tbody tr {
        cursor: pointer;
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

    .stat-card {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
    }

    .stat-card h3 {
        font-size: 2rem;
        margin: 0 0 0.5rem 0;
    }

    .stat-card small {
        opacity: 0.9;
    }

    .stat-card.total { --gradient-start: #6c757d; --gradient-end: #495057; }
    .stat-card.planejamento { --gradient-start: #6c757d; --gradient-end: #495057; }
    .stat-card.andamento { --gradient-start: #0d6efd; --gradient-end: #0b5ed7; }
    .stat-card.concluido { --gradient-start: #198754; --gradient-end: #157347; }
    .stat-card.pausado { --gradient-start: #ffc107; --gradient-end: #ffb300; }
    .stat-card.cancelado { --gradient-start: #dc3545; --gradient-end: #bd2130; }

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
    const USERS_URL = '/api/users';
    const token = document.querySelector('meta[name="api-token"]')?.content;

    // Check if token exists
    if (!token || token === 'null' || token === '') {
        document.addEventListener('DOMContentLoaded', function() {
            console.error('Token não encontrado. Redirecionando para login...');
            window.location.href = '/login';
        });
    }

    let currentTaskId = null;
    let viewTaskModal, editTaskModal, deleteModal;
    let allTasks = [];

    document.addEventListener('DOMContentLoaded', function() {
        viewTaskModal = new bootstrap.Modal(document.getElementById('viewTaskModal'));
        editTaskModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        loadUsers();
        loadTasks();

        // Event listeners
        document.getElementById('filterUser').addEventListener('change', loadTasks);
        document.getElementById('filterStatus').addEventListener('change', loadTasks);
        document.getElementById('sortBy').addEventListener('change', loadTasks);
        document.getElementById('saveEditTaskBtn').addEventListener('click', saveEditTask);
        document.getElementById('confirmDeleteBtn').addEventListener('click', deleteTask);
    });

    function loadUsers() {
        fetch(USERS_URL)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('filterUser');
                    data.data.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = user.name;
                        select.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Erro ao carregar usuários:', error));
    }

    function loadTasks() {
        const userId = document.getElementById('filterUser').value;
        const status = document.getElementById('filterStatus').value;
        const sortBy = document.getElementById('sortBy').value;

        let params = new URLSearchParams();
        if (userId) params.append('user_id', userId);
        if (status) params.append('status', status);

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

        fetch(`${API_URL}?${params}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    allTasks = data.data;
                    renderStats(allTasks);
                    renderTasks(allTasks);
                } else {
                    showError('Erro ao carregar tarefas');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showError('Erro ao carregar tarefas');
            });
    }

    function renderStats(tasks) {
        const stats = {
            total: tasks.length,
            planejamento: tasks.filter(t => t.status === 'Em Planejamento').length,
            andamento: tasks.filter(t => t.status === 'Em Andamento').length,
            concluido: tasks.filter(t => t.status === 'Concluído').length,
            pausado: tasks.filter(t => t.status === 'Pausado').length,
            cancelado: tasks.filter(t => t.status === 'Cancelado').length,
        };

        const html = `
            <div class="col-md-4 col-xl-2">
                <div class="stat-card total">
                    <h3>${stats.total}</h3>
                    <small>Total de Tarefas</small>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div class="stat-card planejamento">
                    <h3>${stats.planejamento}</h3>
                    <small>Em Planejamento</small>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div class="stat-card andamento">
                    <h3>${stats.andamento}</h3>
                    <small>Em Andamento</small>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div class="stat-card concluido">
                    <h3>${stats.concluido}</h3>
                    <small>Concluído</small>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div class="stat-card pausado">
                    <h3>${stats.pausado}</h3>
                    <small>Pausado</small>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div class="stat-card cancelado">
                    <h3>${stats.cancelado}</h3>
                    <small>Cancelado</small>
                </div>
            </div>
        `;

        document.getElementById('statsContainer').innerHTML = html;
    }

    function renderTasks(tasks) {
        const container = document.getElementById('tasksTableContainer');

        if (tasks.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📋</div>
                    <h5 class="text-muted">Nenhuma tarefa encontrada</h5>
                    <p class="text-muted mb-0">Ajuste os filtros para ver resultados</p>
                </div>
            `;
            return;
        }

        let html = `
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40%;">Descrição</th>
                        <th style="width: 20%;">Usuário</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 15%;">Data</th>
                        <th style="width: 10%; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
        `;

        tasks.forEach(task => {
            const statusClass = getStatusBadgeClass(task.status);
            const date = new Date(task.created_at).toLocaleDateString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: '2-digit'
            });

            html += `
                <tr onclick="viewTask(${task.id})">
                    <td>
                        <strong>${escapeHtml(task.description.substring(0, 50))}</strong>
                        ${task.description.length > 50 ? '...' : ''}
                    </td>
                    <td>${escapeHtml(task.user?.name || 'Desconhecido')}</td>
                    <td>
                        <span class="status-badge ${statusClass}">${task.status}</span>
                    </td>
                    <td>${date}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group" onclick="event.stopPropagation();">
                            <button type="button" class="btn btn-outline-primary" onclick="editTask(${task.id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="confirmDelete(${task.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
        `;

        container.innerHTML = html;
    }

    function viewTask(taskId) {
        const task = allTasks.find(t => t.id === taskId);
        if (!task) return;

        document.getElementById('viewTaskDescription').textContent = task.description;
        document.getElementById('viewTaskStatus').innerHTML = `<span class="status-badge ${getStatusBadgeClass(task.status)}">${task.status}</span>`;
        document.getElementById('viewTaskUser').textContent = task.user?.name || 'Desconhecido';
        document.getElementById('viewTaskEmail').textContent = task.user?.email || '-';

        const created = new Date(task.created_at).toLocaleDateString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        const updated = new Date(task.updated_at).toLocaleDateString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        document.getElementById('viewTaskCreated').textContent = created;
        document.getElementById('viewTaskUpdated').textContent = updated;

        currentTaskId = taskId;
        viewTaskModal.show();
    }

    function editTask(taskId) {
        currentTaskId = taskId;
        const task = allTasks.find(t => t.id === taskId);
        if (!task) return;

        document.getElementById('editTaskDescription').value = task.description;
        document.getElementById('editTaskStatus').value = task.status;
        document.getElementById('editFormError').classList.add('d-none');

        viewTaskModal.hide();
        editTaskModal.show();
    }

    function saveEditTask() {
        const description = document.getElementById('editTaskDescription').value.trim();
        const status = document.getElementById('editTaskStatus').value;
        const errorDiv = document.getElementById('editFormError');

        if (!description) {
            errorDiv.textContent = 'Por favor, informe uma descrição';
            errorDiv.classList.remove('d-none');
            return;
        }

        const btn = document.getElementById('saveEditTaskBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Salvando...';

        fetch(`${API_URL}/${currentTaskId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                description: description,
                status: status
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    editTaskModal.hide();
                    loadTasks();
                    showSuccess(data.message);
                } else {
                    errorDiv.textContent = data.message;
                    errorDiv.classList.remove('d-none');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check-circle"></i> Salvar';
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                errorDiv.textContent = 'Erro ao salvar tarefa';
                errorDiv.classList.remove('d-none');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle"></i> Salvar';
            });
    }

    function confirmDelete(taskId) {
        currentTaskId = taskId;
        viewTaskModal.hide();
        deleteModal.show();
    }

    function deleteTask() {
        const btn = document.getElementById('confirmDeleteBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deletando...';

        fetch(`${API_URL}/${currentTaskId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    deleteModal.hide();
                    loadTasks();
                    showSuccess(data.message);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-trash"></i> Deletar';
                    showError(data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-trash"></i> Deletar';
                showError('Erro ao deletar tarefa');
            });
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
        document.body.insertBefore(alert, document.body.firstChild);
        setTimeout(() => alert.remove(), 5000);
    }

    function showSuccess(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show';
        alert.innerHTML = `
            <i class="bi bi-check-circle"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.insertBefore(alert, document.body.firstChild);
        setTimeout(() => alert.remove(), 5000);
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

