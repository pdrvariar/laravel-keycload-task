@extends('layouts.app')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <!-- Voltar -->
        <a href="{{ route('tasks.index') }}" style="color: #667eea; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; transition: all 0.3s ease;" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#667eea'">
            <i class="bi bi-arrow-left"></i> Voltar para Tarefas
        </a>

        <!-- Header -->
        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0;">
                <i class="bi bi-pencil-square"></i> Editar Tarefa
            </h2>
            <p style="color: #64748b; margin-top: 0.5rem; font-size: 1rem;">
                Modifique os dados e status de sua tarefa
            </p>
        </div>

        <!-- Formulário -->
        <div class="card-modern">
            <form id="editTaskForm">
                <!-- Descrição -->
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem; font-size: 0.95rem;">
                        <i class="bi bi-chat-left-text"></i> Descrição da Tarefa
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        placeholder="Descreva sua tarefa em detalhes..."
                        maxlength="1000"
                        style="width: 100%; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; font-family: inherit; resize: vertical; min-height: 150px; transition: all 0.3s ease;"
                        onkeyup="updateCharCount()"
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                        required
                    ></textarea>
                    <div style="display: flex; justify-content: space-between; margin-top: 0.5rem;">
                        <p style="color: #64748b; font-size: 0.85rem; margin: 0;">Máximo 1000 caracteres</p>
                        <p style="color: #94a3b8; font-size: 0.85rem; margin: 0;"><span id="charCount">0</span>/1000</p>
                    </div>
                </div>

                <!-- Status -->
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem; font-size: 0.95rem;">
                        <i class="bi bi-tag"></i> Status da Tarefa
                    </label>
                    <select
                        id="status"
                        name="status"
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; cursor: pointer; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                        required
                    >
                        <option value="Em Planejamento">Em Planejamento</option>
                        <option value="Em Andamento">Em Andamento</option>
                        <option value="Concluído">Concluído</option>
                        <option value="Pausado">Pausado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>

                <!-- Informações de Data -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; padding: 1.5rem; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div>
                        <p style="color: #94a3b8; font-size: 0.85rem; margin: 0; font-weight: 600;">
                            <i class="bi bi-calendar-event"></i> Criado em
                        </p>
                        <p style="color: #1e293b; font-weight: 600; margin: 0.5rem 0 0 0;" id="createdDate">Carregando...</p>
                    </div>
                    <div>
                        <p style="color: #94a3b8; font-size: 0.85rem; margin: 0; font-weight: 600;">
                            <i class="bi bi-arrow-clockwise"></i> Atualizado em
                        </p>
                        <p style="color: #1e293b; font-weight: 600; margin: 0.5rem 0 0 0;" id="updatedDate">Carregando...</p>
                    </div>
                </div>

                <!-- Alerta de Erro -->
                <div id="errorAlert" style="display: none; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; padding: 1rem; margin-bottom: 2rem; border-left: 4px solid #ef4444;">
                    <p style="color: #991b1b; margin: 0; font-weight: 500;">
                        <i class="bi bi-exclamation-circle"></i> <span id="errorMessage"></span>
                    </p>
                </div>

                <!-- Alerta de Sucesso -->
                <div id="successAlert" style="display: none; background: #dcfce7; border: 1px solid #bbf7d0; border-radius: 8px; padding: 1rem; margin-bottom: 2rem; border-left: 4px solid #10b981;">
                    <p style="color: #166534; margin: 0; font-weight: 500;">
                        <i class="bi bi-check-circle"></i> <span id="successMessage">Tarefa atualizada com sucesso!</span>
                    </p>
                </div>

                <!-- Botões -->
                <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 1rem; grid-template-areas: 'cancel save' 'delete delete';">
                    <a href="{{ route('tasks.index') }}" style="grid-area: cancel; background: white; border: 2px solid #e2e8f0; color: #475569; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                    <button type="submit" style="grid-area: save; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <i class="bi bi-check-circle"></i> Salvar Alterações
                    </button>
                    <button type="button" id="deleteBtn" style="grid-area: delete; background: white; border: 2px solid #ef4444; color: #ef4444; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='white'">
                        <i class="bi bi-trash"></i> Deletar Tarefa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const token = document.querySelector('meta[name="api-token"]')?.content;
        const taskId = window.location.pathname.split('/')[2];

        // Check if token exists
        if (!token || token === 'null' || token === '') {
            document.addEventListener('DOMContentLoaded', function() {
                console.error('Token não encontrado. Redirecionando para login...');
                window.location.href = '/login';
            });
        }

        function updateCharCount() {
            const count = document.getElementById('description').value.length;
            document.getElementById('charCount').textContent = count;
        }

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('pt-BR', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        async function loadTask() {
            try {
                const response = await fetch(`/api/tasks/${taskId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    if (response.status === 401) {
                        throw new Error('Sua sessão expirou. Por favor, faça login novamente.');
                    }
                    throw new Error('Erro ao carregar tarefa');
                }

                const data = await response.json();
                const task = data.data;

                document.getElementById('description').value = task.description;
                document.getElementById('status').value = task.status;
                document.getElementById('createdDate').textContent = formatDate(task.created_at);
                document.getElementById('updatedDate').textContent = formatDate(task.updated_at);

                updateCharCount();
            } catch (error) {
                showError('Erro ao carregar tarefa');
            }
        }

        document.getElementById('editTaskForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const description = document.getElementById('description').value;
            const status = document.getElementById('status').value;

            if (!description || !status) {
                showError('Preencha todos os campos obrigatórios');
                return;
            }

            try {
                const response = await fetch(`/api/tasks/${taskId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({
                        description,
                        status
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 401) {
                        throw new Error('Sua sessão expirou. Por favor, faça login novamente.');
                    }
                    throw new Error(data.message || 'Erro ao atualizar tarefa');
                }

                showSuccess('Tarefa atualizada com sucesso!');
                setTimeout(() => {
                    window.location.href = '{{ route('tasks.index') }}';
                }, 1500);
            } catch (error) {
                showError(error.message);
            }
        });

        document.getElementById('deleteBtn').addEventListener('click', async () => {
            if (!confirm('Tem certeza que deseja deletar esta tarefa? Esta ação não pode ser desfeita.')) {
                return;
            }

            try {
                const response = await fetch(`/api/tasks/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 401) {
                        throw new Error('Sua sessão expirou. Por favor, faça login novamente.');
                    }
                    throw new Error(data.message || 'Erro ao deletar tarefa');
                }

                window.location.href = '{{ route('tasks.index') }}';
            } catch (error) {
                showError(error.message);
            }
        });

        function showError(message) {
            const alert = document.getElementById('errorAlert');
            document.getElementById('errorMessage').textContent = message;
            alert.style.display = 'block';
            alert.scrollIntoView({ behavior: 'smooth' });
        }

        function showSuccess(message) {
            const alert = document.getElementById('successAlert');
            document.getElementById('successMessage').textContent = message;
            alert.style.display = 'block';
            alert.scrollIntoView({ behavior: 'smooth' });
        }

        document.addEventListener('DOMContentLoaded', loadTask);
    </script>
@endsection

