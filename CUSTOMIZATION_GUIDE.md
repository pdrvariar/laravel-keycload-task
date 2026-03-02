# 🎨 Guia de Customização - Task Controller

Este guia mostra como personalizar o dashboard conforme suas necessidades.

---

## 1️⃣ Alterar Cores Primárias

### Opção 1: Cores Sólidas
Edite o arquivo `resources/views/layouts/app.blade.php`, procure pela seção `:root` e modifique:

```css
:root {
    --primary-color: #3b82f6;        /* Azul */
    --primary-dark: #1d4ed8;         /* Azul Escuro */
    --secondary-color: #ef4444;      /* Vermelho */
    --success-color: #10b981;        /* Verde */
    --warning-color: #f59e0b;        /* Âmbar */
    --danger-color: #ef4444;         /* Vermelho */
}
```

### Opção 2: Gradientes Personalizados
Procure por `.modern-header` e modifique:

```css
.modern-header {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}
```

### Exemplos de Combinações Bonitas

#### Verde & Azul Turquesa
```css
background: linear-gradient(135deg, #10b981 0%, #0891b2 100%);
```

#### Laranja & Vermelho
```css
background: linear-gradient(135deg, #f97316 0%, #dc2626 100%);
```

#### Roxo & Magenta
```css
background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
```

#### Azul & Ciano
```css
background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
```

---

## 2️⃣ Alterar Logo/Branding

### Adicionar Logo em PNG

No arquivo `resources/views/partials/header.blade.php`, substitua:

```html
<!-- ANTES -->
<div class="header-brand-icon">
    <i class="bi bi-checkbox2-checked"></i>
</div>

<!-- DEPOIS -->
<div class="header-brand-icon" style="background: transparent; border: none;">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
</div>
```

### Alterar Texto do Header

```html
<div class="header-brand-text">
    <h1>Seu App Name</h1>
    <p>Sua Empresa</p>
</div>
```

---

## 3️⃣ Personalizar Footer

Edite o arquivo `resources/views/partials/footer.blade.php`:

```html
<footer class="modern-footer">
    <div class="footer-content">
        <div class="footer-company">
            © 2025 <strong>Sua Empresa</strong> - Todos os direitos reservados
        </div>
        <div class="footer-year">
            Desenvolvido com ❤️ por seu nome
        </div>
    </div>
</footer>
```

---

## 4️⃣ Adicionar Novo Menu no Sidebar

Edite `resources/views/partials/sidebar.blade.php`:

```blade
<li>
    <a href="{{ route('relatorios') }}" class="@if(request()->routeIs('relatorios.*')) active @endif">
        <i class="bi bi-graph-up"></i>
        <span>Relatórios</span>
    </a>
</li>

<li>
    <a href="{{ route('calendario') }}" class="@if(request()->routeIs('calendario')) active @endif">
        <i class="bi bi-calendar-event"></i>
        <span>Calendário</span>
    </a>
</li>

<li>
    <a href="{{ route('configuracoes') }}" class="@if(request()->routeIs('configuracoes')) active @endif">
        <i class="bi bi-gear"></i>
        <span>Configurações</span>
    </a>
</li>
```

---

## 5️⃣ Adicionar Ícones Customizados

A plataforma usa [Bootstrap Icons](https://icons.getbootstrap.com/). Alguns exemplos:

```html
<!-- Gráficos -->
<i class="bi bi-graph-up"></i>
<i class="bi bi-bar-chart"></i>
<i class="bi bi-pie-chart"></i>

<!-- Comunicação -->
<i class="bi bi-chat"></i>
<i class="bi bi-envelope"></i>
<i class="bi bi-telephone"></i>

<!-- Usuários -->
<i class="bi bi-person"></i>
<i class="bi bi-people"></i>
<i class="bi bi-person-check"></i>

<!-- Documento -->
<i class="bi bi-file-text"></i>
<i class="bi bi-download"></i>
<i class="bi bi-upload"></i>

<!-- Utilidades -->
<i class="bi bi-settings"></i>
<i class="bi bi-gear"></i>
<i class="bi bi-sliders"></i>
```

---

## 6️⃣ Modificar Cards do Dashboard

No arquivo `resources/views/user/dashboard.blade.php`, você pode alterar os cards:

### Alterar Título
```blade
<h3 class="card-title">Meu Card Customizado</h3>
```

### Alterar Ícone
```html
<div class="card-icon primary">
    <i class="bi bi-seu-novo-icone"></i>
</div>
```

### Alterar Cor do Card
```html
<div class="card-icon success">      <!-- success, warning, danger, primary -->
    <i class="bi bi-check-circle"></i>
</div>
```

---

## 7️⃣ Ajustar Espaçamento

### Margin (espaço externo)
```html
<!-- Remover espaço embaixo -->
<div style="margin-bottom: 0;">

<!-- Aumentar espaço -->
<div style="margin-bottom: 3rem;">

<!-- Espaço em todos os lados -->
<div style="margin: 2rem;">
```

### Padding (espaço interno)
```html
<!-- Sem padding -->
<div style="padding: 0;">

<!-- Padding padrão -->
<div style="padding: 1.5rem;">

<!-- Padding maior -->
<div style="padding: 2rem;">
```

---

## 8️⃣ Modificar Tipografia

### Tamanho de Fonte
```css
/* Extra grande -->
font-size: 2.5rem;

/* Grande -->
font-size: 1.8rem;

/* Normal -->
font-size: 1rem;

/* Pequeno -->
font-size: 0.9rem;
```

### Peso da Fonte
```css
font-weight: 700;  /* Bold */
font-weight: 600;  /* Semi-bold */
font-weight: 500;  /* Medium */
font-weight: 400;  /* Normal */
font-weight: 300;  /* Light */
```

---

## 9️⃣ Adicionar Modo Escuro (Dark Mode)

Adicione ao CSS:

```css
@media (prefers-color-scheme: dark) {
    body {
        background: #0f172a;
        color: #f1f5f9;
    }

    .card-modern {
        background: #1e293b;
        border-color: #334155;
    }

    .sidebar {
        background: #1e293b;
        border-right-color: #334155;
    }
}
```

---

## 🔟 Alterar Animações

### Aumentar Velocidade de Animação
Procure por `transition: all 0.3s ease;` e mude para:
```css
transition: all 0.1s ease;  /* Mais rápido */
transition: all 0.5s ease;  /* Mais lento */
```

### Alterar Tipo de Animação
```css
transition: all 0.3s linear;     /* Linear */
transition: all 0.3s ease-in;    /* Entrada */
transition: all 0.3s ease-out;   /* Saída */
transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);  /* Custom */
```

---

## 1️⃣1️⃣ Alterar Status de Tarefas

No arquivo `resources/views/tasks/index.blade.php`, localize `getStatusClass()`:

```javascript
function getStatusClass(status) {
    const statusMap = {
        'Em Planejamento': 'planning',
        'Em Andamento': 'in-progress',
        'Concluído': 'done',
        'Pausado': 'paused',
        'Cancelado': 'cancelled',
        'Novo Status': 'novo-status'  // ADICIONE AQUI
    };
    return statusMap[status] || 'planning';
}
```

Depois customize a cor em CSS:

```css
.task-status.novo-status {
    background: #seu-cor-claro;
    color: #sua-cor-escura;
}
```

---

## 1️⃣2️⃣ Alterar Layout do Grid de Cards

Em `resources/views/user/dashboard.blade.php`:

```html
<!-- ANTES: Automático -->
<div class="dashboard-grid">

<!-- DEPOIS: Customizado -->
<div class="dashboard-grid" style="grid-template-columns: 1fr 2fr;">
```

Opções:
- `grid-template-columns: 1fr;` - 1 coluna
- `grid-template-columns: 1fr 1fr;` - 2 colunas
- `grid-template-columns: 1fr 1fr 1fr;` - 3 colunas
- `grid-template-columns: 2fr 1fr;` - 2 col (primeira maior)

---

## 1️⃣3️⃣ Adicionar Bordas Customizadas

```html
<!-- Borda fina -->
<div style="border: 1px solid #e2e8f0;">

<!-- Borda grossa -->
<div style="border: 3px solid #667eea;">

<!-- Borda apenas no lado esquerdo -->
<div style="border-left: 4px solid #667eea;">

<!-- Borda arredondada -->
<div style="border-radius: 8px;">

<!-- Borda circular -->
<div style="border-radius: 50%;">
```

---

## 1️⃣4️⃣ Adicionar Sombras

```css
/* Sombra suave -->
box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);

/* Sombra média -->
box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);

/* Sombra forte -->
box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);

/* Sombra colorida -->
box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
```

---

## 1️⃣5️⃣ Exemplos de Customizações Completas

### Exemplo 1: Tema Verde Relaxante

```css
:root {
    --primary-color: #059669;
    --primary-dark: #047857;
}

.modern-header {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
}
```

### Exemplo 2: Tema Corporativo Azul

```css
:root {
    --primary-color: #1e40af;
    --primary-dark: #1e3a8a;
}

.modern-header {
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
}
```

### Exemplo 3: Tema Criativo Múlti-cor

```css
:root {
    --primary-color: #db2777;
    --primary-dark: #be185d;
}

.modern-header {
    background: linear-gradient(135deg, #db2777 0%, #be185d 100%);
}

.card-modern:hover {
    transform: translateY(-12px);
    box-shadow: 0 30px 50px rgba(219, 39, 119, 0.2);
}
```

---

## 🎨 Combinações de Cores Recomendadas

### Profissionais
| Primária | Secundária | Uso |
|----------|-----------|-----|
| #1e40af  | #1e3a8a   | Corporativo |
| #059669  | #047857   | Saúde/Bem-estar |
| #7c3aed  | #6d28d9   | Tech/Inovação |

### Modernas
| Primária | Secundária | Uso |
|----------|-----------|-----|
| #ec4899  | #db2777   | Criativo |
| #0891b2  | #0e7490   | Tech/Startup |
| #f59e0b  | #d97706   | Energia |

---

## 📱 Ajustes para Mobile

Procure por `@media (max-width: 768px)` no CSS:

```css
@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        left: -280px;
        transition: left 0.3s ease;
    }

    .main-content {
        margin-left: 0;
        padding: 1rem;
    }

    .dashboard-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    h1 {
        font-size: 1.5rem;
    }
}
```

---

## ✅ Checklist de Customização

- [ ] Alterar cor primária
- [ ] Adicionar logo da empresa
- [ ] Mudar nome da empresa (header + footer)
- [ ] Adicionar novos menus
- [ ] Ajustar textos
- [ ] Testar em mobile
- [ ] Verificar cores em diferentes navegadores
- [ ] Testar responsividade

---

## 🔗 Recursos Úteis

- [Bootstrap Icons](https://icons.getbootstrap.com/)
- [Color Picker](https://colorpicker.com/)
- [Gradient Generator](https://cssgradient.io/)
- [Box Shadow Generator](https://www.cssmatic.com/box-shadow)

---

**Divirta-se customizando! 🎉**

*Qualquer dúvida, consulte DASHBOARD_DESIGN_GUIDE.md*

