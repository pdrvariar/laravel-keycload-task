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
                <i class="bi bi-plus-circle"></i> Nova Tarefa
            </h2>
            <p style="color: #64748b; margin-top: 0.5rem; font-size: 1rem;">
                Crie uma nova tarefa para sua lista de afazeres
            </p>
        </div>

        <!-- Formulário -->
        <div class="card-modern">
            <form id="createTaskForm">
                <!-- Título -->
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem; font-size: 0.95rem;">
                        <i class="bi bi-bookmark"></i> Título da Tarefa
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="Digite o título da tarefa..."
                        maxlength="255"
                        style="width: 100%; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; font-family: inherit; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                    />
                    <p style="color: #64748b; font-size: 0.85rem; margin-top: 0.5rem; margin-bottom: 0;">Opcional - Se deixar em branco, será definido como "(SEM TITULO)"</p>
                </div>

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
                        <i class="bi bi-tag"></i> Status Inicial
                    </label>
                    <select
                        id="status"
                        name="status"
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; cursor: pointer; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                        required
                    >
                        <option value="Em Planejamento" selected>Em Planejamento</option>
                        <option value="Em Andamento">Em Andamento</option>
                        <option value="Concluído">Concluído</option>
                        <option value="Pausado">Pausado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                    <p style="color: #64748b; font-size: 0.85rem; margin-top: 0.5rem; margin-bottom: 0;">Escolha o status inicial da sua tarefa</p>
                </div>

                <!-- Alerta de Dica -->
                <div style="background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%); border: 1px solid #7dd3fc; border-radius: 8px; padding: 1rem; margin-bottom: 2rem; border-left: 4px solid #0ea5e9;">
                    <p style="color: #0369a1; margin: 0; font-weight: 500;">
                        <i class="bi bi-info-circle"></i> Dica: Você pode editar a tarefa a qualquer momento após criá-la!
                    </p>
                </div>

                <!-- Erro -->
                <div id="errorAlert" style="display: none; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; padding: 1rem; margin-bottom: 2rem; border-left: 4px solid #ef4444;">
                    <p style="color: #991b1b; margin: 0; font-weight: 500;">
                        <i class="bi bi-exclamation-circle"></i> <span id="errorMessage"></span>
                    </p>
                </div>

                <!-- Botões -->
                <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 1rem;">
                    <a href="{{ route('tasks.index') }}" style="background: white; border: 2px solid #e2e8f0; color: #475569; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                    <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <i class="bi bi-check-circle"></i> Criar Tarefa
                    </button>
                </div>
            </form>
        </div>

        <!-- Dicas -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
            <div class="card-modern" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-left: 4px solid #667eea;">
                <h4 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-top: 0; margin-bottom: 1rem;">
                    <i class="bi bi-lightbulb"></i> Boas Práticas
                </h4>
                <ul style="margin: 0; padding-left: 1.5rem; color: #64748b; font-size: 0.95rem;">
                    <li>Seja específico na descrição</li>
                    <li>Use linguagem clara e objetiva</li>
                    <li>Divida tarefas grandes em menores</li>
                </ul>
            </div>

            <div class="card-modern" style="background: linear-gradient(135deg, #f0fdf4 0%, #f1fdf5 100%); border-left: 4px solid #10b981;">
                <h4 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-top: 0; margin-bottom: 1rem;">
                    <i class="bi bi-check-circle"></i> Dicas de Produtividade
                </h4>
                <ul style="margin: 0; padding-left: 1.5rem; color: #64748b; font-size: 0.95rem;">
                    <li>Priorize tarefas importantes</li>
                    <li>Estabeleça prazos realistas</li>
                    <li>Acompanhe seu progresso</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        const token = document.querySelector('meta[name="api-token"]')?.content;

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

        document.getElementById('createTaskForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const form = e.target;
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value;
            const status = document.getElementById('status').value;

            if (!description || !status) {
                showError('Preencha todos os campos obrigatórios');
                return;
            }

            try {
                const response = await fetch('/api/tasks', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({
                        title: title || '(SEM TITULO)',
                        description,
                        status
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 401) {
                        throw new Error('Sua sessão expirou. Por favor, faça login novamente.');
                    }
                    throw new Error(data.message || 'Erro ao criar tarefa');
                }

                // Sucesso
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
    </script>
@endsection
