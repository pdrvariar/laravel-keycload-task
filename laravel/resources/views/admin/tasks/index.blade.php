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
                        <option value="title">Título (A-Z)</option>
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
                <h5 class="modal-title" id="viewTaskTitleHeader">Detalhes da Tarefa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted small">Título</label>
                        <h4 id="viewTaskTitleContent" class="fw-bold"></h4>
                    </div>
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
                        <label class="form-label fw-bold">Título</label>
                        <input type="text" class="form-control" id="editTaskTitle" maxlength="255">
                    </div>
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

<!-- Modal Clonar Tarefa -->
<div class="modal fade" id="cloneModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Clonar Tarefa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Deseja criar uma cópia desta tarefa?</p>
                <div class="mb-3">
                    <label class="form-label fw-bold">Novo Título</label>
                    <input type="text" class="form-control" id="cloneTaskTitle" maxlength="255">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nova Descrição</label>
                    <textarea class="form-control" id="cloneTaskDescription" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info text-white" id="confirmCloneBtn">
                    <i class="bi bi-copy"></i> Clonar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Exclusão em Lote -->
<div class="modal fade" id="batchDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Exclusão em Lote</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja deletar <span id="batchCount">0</span> tarefas selecionadas?</p>
                <p class="text-danger fw-bold">Esta ação não pode ser desfeita!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmBatchDeleteBtn">
                    <i class="bi bi-trash"></i> Deletar Selecionadas
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

    /* Datatable Customization */
    .dataTables_wrapper .dataTables_length select {
        padding-right: 2rem !important;
    }

    .action-icon {
        cursor: pointer;
        padding: 5px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .action-icon:hover {
        background-color: rgba(0,0,0,0.05);
    }

    .selected-row {
        background-color: rgba(13, 110, 253, 0.05) !important;
    }
</style>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.bootstrap5.min.css">

<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>

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
    let viewTaskModal, editTaskModal, deleteModal, cloneModal, batchDeleteModal;
    let allTasks = [];
    let dataTable = null;

    document.addEventListener('DOMContentLoaded', function() {
        viewTaskModal = new bootstrap.Modal(document.getElementById('viewTaskModal'));
        editTaskModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        cloneModal = new bootstrap.Modal(document.getElementById('cloneModal'));
        batchDeleteModal = new bootstrap.Modal(document.getElementById('batchDeleteModal'));

        loadUsers();
        loadTasks();

        // Event listeners
        document.getElementById('filterUser').addEventListener('change', loadTasks);
        document.getElementById('filterStatus').addEventListener('change', loadTasks);
        document.getElementById('sortBy').addEventListener('change', loadTasks);
        document.getElementById('saveEditTaskBtn').addEventListener('click', saveEditTask);
        document.getElementById('confirmDeleteBtn').addEventListener('click', deleteTask);
        document.getElementById('confirmCloneBtn').addEventListener('click', cloneTask);
        document.getElementById('confirmBatchDeleteBtn').addEventListener('click', deleteBatchTasks);
        document.getElementById('editFromViewBtn').addEventListener('click', function() {
            editTask(currentTaskId);
        });
    });

    function loadUsers() {
        fetch(USERS_URL, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('filterUser');
                    // Limpar opções existentes exceto a primeira
                    while (select.options.length > 1) {
                        select.remove(1);
                    }
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
        } else if (sortBy === 'title') {
            params.append('sort_by', 'title');
            params.append('sort_order', 'asc');
        }

        // Show loading state
        const container = document.getElementById('tasksTableContainer');
        container.innerHTML = `
            <div class="text-center p-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p class="mt-2">Carregando tarefas...</p>
            </div>
        `;

        fetch(`${API_URL}?${params}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    allTasks = data.data;
                    renderStats(allTasks);
                    initDataTable(allTasks);
                } else {
                    showError('Erro ao carregar tarefas: ' + (data.message || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showError('Erro ao carregar tarefas. Verifique sua conexão.');
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

    function initDataTable(tasks) {
        const container = document.getElementById('tasksTableContainer');

        // Destroy existing table if any
        if (dataTable) {
            dataTable.destroy();
            dataTable = null;
        }

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
            <div class="p-3">
                <button id="btnBatchDelete" class="btn btn-danger btn-sm mb-3 d-none">
                    <i class="bi bi-trash"></i> Excluir Selecionados (<span id="selectedCount">0</span>)
                </button>
                <table id="tasksTable" class="table table-hover dt-responsive nowrap" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th style="width: 20%;">Título</th>
                            <th style="width: 25%;">Descrição</th>
                            <th style="width: 15%;">Usuário</th>
                            <th style="width: 10%;">Status</th>
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
                year: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });

            html += `
                <tr data-id="${task.id}">
                    <td><input type="checkbox" class="form-check-input task-checkbox" value="${task.id}"></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="text-truncate fw-bold" style="max-width: 200px;" title="${escapeHtml(task.title)}">
                                ${escapeHtml(task.title || '(Sem Título)')}
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="text-truncate" style="max-width: 300px;" title="${escapeHtml(task.description)}">
                                ${escapeHtml(task.description)}
                            </span>
                        </div>
                    </td>
                    <td>${escapeHtml(task.user?.name || 'Desconhecido')}</td>
                    <td>
                        <span class="status-badge ${statusClass}">${task.status}</span>
                    </td>
                    <td>${date}</td>
                    <td class="text-center">
                        <i class="bi bi-eye text-primary action-icon me-2" onclick="viewTask(${task.id})" title="Visualizar"></i>
                        <i class="bi bi-pencil text-warning action-icon me-2" onclick="editTask(${task.id})" title="Editar"></i>
                        <i class="bi bi-copy text-info action-icon me-2" onclick="confirmClone(${task.id})" title="Clonar"></i>
                        <i class="bi bi-trash text-danger action-icon" onclick="confirmDelete(${task.id})" title="Excluir"></i>
                    </td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
        `;

        container.innerHTML = html;

        // Initialize DataTable
        dataTable = $('#tasksTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            responsive: true,
            order: [[5, 'desc']], // Sort by date by default (index 5 now)
            columnDefs: [
                { orderable: false, targets: [0, 6] } // Disable sorting for checkbox and actions
            ],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]]
        });

        // Checkbox handling
        $('#selectAll').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.task-checkbox').prop('checked', isChecked);
            updateBatchDeleteButton();
        });

        $(document).on('change', '.task-checkbox', function() {
            updateBatchDeleteButton();

            // Update "Select All" checkbox state
            const allChecked = $('.task-checkbox:checked').length === $('.task-checkbox').length;
            $('#selectAll').prop('checked', allChecked);
        });
    }

    function updateBatchDeleteButton() {
        const selectedCount = $('.task-checkbox:checked').length;
        const btn = $('#btnBatchDelete');
        const countSpan = $('#selectedCount');

        countSpan.text(selectedCount);

        if (selectedCount > 0) {
            btn.removeClass('d-none');
        } else {
            btn.addClass('d-none');
        }
    }

    function deleteBatchTasks() {
        const selectedIds = [];
        $('.task-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        const btn = document.getElementById('confirmBatchDeleteBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deletando...';

        // Process deletions sequentially or in parallel
        const promises = selectedIds.map(id => {
            return fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
        });

        Promise.all(promises)
            .then(responses => {
                batchDeleteModal.hide();
                loadTasks();
                showSuccess(`${selectedIds.length} tarefas excluídas com sucesso!`);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-trash"></i> Deletar Selecionadas';
            })
            .catch(error => {
                console.error('Erro:', error);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-trash"></i> Deletar Selecionadas';
                showError('Erro ao deletar algumas tarefas');
            });
    }

    function viewTask(taskId) {
        const task = allTasks.find(t => t.id === taskId);
        if (!task) return;

        document.getElementById('viewTaskTitleContent').textContent = task.title || '(Sem Título)';
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

        document.getElementById('editTaskTitle').value = task.title || '';
        document.getElementById('editTaskDescription').value = task.description;
        document.getElementById('editTaskStatus').value = task.status;
        document.getElementById('editFormError').classList.add('d-none');

        // Reset button state
        const btn = document.getElementById('saveEditTaskBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle"></i> Salvar';

        viewTaskModal.hide();
        editTaskModal.show();
    }

    function saveEditTask() {
        const title = document.getElementById('editTaskTitle').value.trim();
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
                title: title,
                description: description,
                status: status
            })
        })
            .then(response => response.json())
            .then(data => {
                // Always reset button state
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle"></i> Salvar';

                if (data.success) {
                    editTaskModal.hide();
                    loadTasks();
                    showSuccess(data.message);
                } else {
                    errorDiv.textContent = data.message;
                    errorDiv.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                errorDiv.textContent = 'Erro ao salvar tarefa: ' + error.message;
                errorDiv.classList.remove('d-none');

                // Reset button state on error
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

    function confirmClone(taskId) {
        currentTaskId = taskId;
        const task = allTasks.find(t => t.id === taskId);
        if (!task) return;

        document.getElementById('cloneTaskTitle').value = (task.title || 'Tarefa') + ' (Cópia)';
        document.getElementById('cloneTaskDescription').value = task.description;
        cloneModal.show();
    }

    function cloneTask() {
        const title = document.getElementById('cloneTaskTitle').value.trim();
        const description = document.getElementById('cloneTaskDescription').value.trim();
        const btn = document.getElementById('confirmCloneBtn');

        if (!description) {
            alert('Por favor, informe uma descrição');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Clonando...';

        fetch(`${API_URL}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                title: title,
                description: description,
                status: 'Em Planejamento'
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cloneModal.hide();
                    loadTasks();
                    showSuccess('Tarefa clonada com sucesso!');
                } else {
                    showError(data.message);
                }
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-copy"></i> Clonar';
            })
            .catch(error => {
                console.error('Erro:', error);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-copy"></i> Clonar';
                showError('Erro ao clonar tarefa');
            });
    }

    // Batch delete trigger
    document.getElementById('btnBatchDelete').addEventListener('click', function() {
        const count = $('.task-checkbox:checked').length;
        document.getElementById('batchCount').textContent = count;
        batchDeleteModal.show();
    });

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
        alert.style.position = 'fixed';
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.innerHTML = `
            <i class="bi bi-exclamation-circle"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 5000);
    }

    function showSuccess(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show';
        alert.style.position = 'fixed';
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.innerHTML = `
            <i class="bi bi-check-circle"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 5000);
    }

    function escapeHtml(text) {
        if (!text) return '';
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
