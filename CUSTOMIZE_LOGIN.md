# 🎨 Guia de Personalização - Tela de Login

Este guia mostra como personalizar a tela de login de acordo com suas necessidades.

## 1️⃣ Mudar Cores do Gradiente

### Localização
Arquivo: `resources/views/auth/login.blade.php`

### Código Atual
```css
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
}
```

### Exemplos de Personalizações

#### Azul Profissional
```css
background: linear-gradient(135deg, #1e40af 0%, #0369a1 50%, #0891b2 100%);
```

#### Verde Moderno
```css
background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
```

#### Laranja Energético
```css
background: linear-gradient(135deg, #ea580c 0%, #f59e0b 50%, #fbbf24 100%);
```

#### Cinza Elegante
```css
background: linear-gradient(135deg, #4b5563 0%, #6b7280 50%, #9ca3af 100%);
```

#### Rosa Feminino
```css
background: linear-gradient(135deg, #ec4899 0%, #f43f5e 50%, #fb7185 100%);
```

---

## 2️⃣ Mudar Logo e Nome da Aplicação

### Localização
Procure por esta seção na view:

```html
<div class="logo-icon">✓</div>
<div class="logo-text">Task Controller</div>
<div class="logo-subtitle">Gerencie suas tarefas com eficiência</div>
```

### Exemplo: Mudar para sua Marca

```html
<!-- Opção 1: Usar Emoji diferente -->
<div class="logo-icon">⚡</div>
<div class="logo-text">Seu App</div>
<div class="logo-subtitle">Descrição do seu aplicativo</div>

<!-- Opção 2: Usar Imagem PNG -->
<div style="margin-bottom: 16px;">
    <img src="/images/logo.png" alt="Logo" style="width: 56px; height: 56px;">
</div>
<div class="logo-text">Seu App</div>
<div class="logo-subtitle">Descrição do seu aplicativo</div>

<!-- Opção 3: Usar Iniciais -->
<div class="logo-icon" style="font-size: 24px; font-weight: 700;">SA</div>
<div class="logo-text">Seu App</div>
<div class="logo-subtitle">Descrição do seu aplicativo</div>
```

---

## 3️⃣ Adicionar Campos de Formulário

Se quiser adicionar campos (email, senha, etc.), procure por:

```html
<form method="POST" action="{{ route('login') }}">
    @csrf
    <button type="submit" class="login-button">
        <!-- ... botão ... -->
    </button>
</form>
```

E substitua por:

```html
<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Campo Email -->
    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1f2937; font-size: 14px;">
            Email
        </label>
        <input type="email" name="email" placeholder="seu@email.com"
               style="width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
               onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
               onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none';"
               required>
    </div>

    <!-- Campo Senha -->
    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1f2937; font-size: 14px;">
            Senha
        </label>
        <input type="password" name="password" placeholder="••••••••"
               style="width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
               onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
               onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none';"
               required>
    </div>

    <!-- Botão -->
    <button type="submit" class="login-button">
        🔑 Fazer Login
    </button>
</form>
```

---

## 4️⃣ Personalizar Features (Lado Esquerdo)

### Código Atual
```html
<div class="feature-item">
    <div class="feature-icon">📋</div>
    <div class="feature-content">
        <h3>Organização Inteligente</h3>
        <p>Mantenha suas tarefas organizadas e priorizadas</p>
    </div>
</div>
```

### Exemplo: Adicionar Novas Features

```html
<!-- Feature 1: Relatórios -->
<div class="feature-item">
    <div class="feature-icon">📊</div>
    <div class="feature-content">
        <h3>Relatórios Detalhados</h3>
        <p>Analise o progresso com gráficos e estatísticas</p>
    </div>
</div>

<!-- Feature 2: Integração -->
<div class="feature-item">
    <div class="feature-icon">🔗</div>
    <div class="feature-content">
        <h3>Integrações Poderosas</h3>
        <p>Conecte com suas ferramentas favoritas</p>
    </div>
</div>

<!-- Feature 3: Cloud -->
<div class="feature-item">
    <div class="feature-icon">☁️</div>
    <div class="feature-content">
        <h3>Sincronização na Nuvem</h3>
        <p>Acesse de qualquer dispositivo, em qualquer lugar</p>
    </div>
</div>
```

---

## 5️⃣ Mudar Textos e Mensagens

Localize e mude estes textos conforme necessário:

### Título Principal
Procure por:
```html
<h2 class="form-title">Bem-vindo de volta</h2>
```

Mude para:
```html
<h2 class="form-title">Faça Login Agora</h2>
```

### Subtítulo
Procure por:
```html
<p class="form-subtitle">Faça login para acessar seu painel</p>
```

Mude para:
```html
<p class="form-subtitle">Acesse seu gerenciador de tarefas</p>
```

### Botão
Procure por:
```html
Conectar com Keycloak
```

Mude para:
```html
Login com Keycloak
```

### Segurança
Procure por:
```html
<strong>🔒 Conexão Segura</strong>
Seu login é protegido por Keycloak com autenticação OAuth 2.0
```

Mude para:
```html
<strong>🔒 100% Seguro</strong>
Autenticação de nível empresarial
```

### Help Text
Procure por:
```html
Primeira vez aqui? <br> Peça ao administrador para criar sua conta
```

Mude para:
```html
Não tem acesso? <br> Entre em contato com o suporte
```

---

## 6️⃣ Ajustar Layout

### Aumentar Padding (Espaço Interno)

Procure por:
```css
.login-left {
    padding: 60px 40px;
}

.login-right {
    padding: 60px 40px;
}
```

Mude para:
```css
.login-left {
    padding: 80px 50px; /* Aumentado */
}

.login-right {
    padding: 80px 50px; /* Aumentado */
}
```

### Aumentar Border Radius (Cantos Redondos)

Procure por:
```css
border-radius: 20px;
```

Mude para:
```css
border-radius: 30px; /* Mais redondo */
/* ou */
border-radius: 10px; /* Mais angular */
```

### Ajustar Altura do Card

Procure por:
```css
height: 100vh;
```

Mude para:
```css
height: auto; /* Altura automática */
/* ou */
min-height: 600px; /* Altura mínima */
```

---

## 7️⃣ Efeitos e Animações

### Desabilitar Animações (Carregamento Mais Rápido)

Procure por:
```css
animation: slideInRight 0.8s ease-out;
```

Substitua por:
```css
/* animation: slideInRight 0.8s ease-out; */ /* Comentado */
```

Ou mude o tempo:
```css
animation: slideInRight 0.3s ease-out; /* Mais rápido */
```

### Aumentar Velocidade do Hover

Procure por:
```css
.login-button:hover {
    transition: all 0.3s ease;
}
```

Mude para:
```css
.login-button:hover {
    transition: all 0.1s ease; /* Mais rápido */
}
```

---

## 8️⃣ Temas Completos

### Tema Dark Mode

Procure por:
```css
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
}
```

Mude para:
```css
body {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
}
```

E o container:
```css
.login-container {
    background: white; /* Mude para dark */
}

.form-title {
    color: #1f2937; /* Mude para light */
}
```

### Tema Corporativo

```css
/* Paleta azul corporativa */
body {
    background: linear-gradient(135deg, #003366 0%, #004080 50%, #0055b3 100%);
}

.logo-icon {
    background: linear-gradient(135deg, #003366 0%, #004080 100%);
}

.login-button {
    background: linear-gradient(135deg, #004080 0%, #0055b3 100%);
}
```

---

## 9️⃣ Adicionar Imagem de Fundo

Mude o gradiente por uma imagem:

```css
body {
    background: url('/images/background.jpg') center/cover no-repeat;
    background-attachment: fixed;
}

/* Para melhorar legibilidade, adicione uma sobreposição */
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: -1;
}
```

---

## 🔟 Validação de Erros

Se quiser adicionar mensagens de erro:

```html
@if ($errors->any())
    <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
        @foreach ($errors->all() as $error)
            <p style="margin: 4px 0;">❌ {{ $error }}</p>
        @endforeach
    </div>
@endif
```

---

## 💡 Dicas Extras

1. **Testar Responsividade**: Use F12 (DevTools) e teste em diferentes tamanhos de tela
2. **Performance**: Otimize imagens antes de adicionar
3. **Acessibilidade**: Mantenha contraste adequado entre cores
4. **Compatibilidade**: Teste em navegadores diferentes
5. **Cache**: Limpe o cache do navegador ao fazer mudanças

---

## 🆘 Problemas Comuns

### Cores não aparecem?
- Limpe o cache do navegador (Ctrl+Shift+Del)
- Verifique a sintaxe hexadecimal (#RRGGBB)

### Animações não funcionam?
- Verifique se não estão comentadas
- Teste em navegadores modernos

### Botão não clica?
- Verifique a ação do formulário em `routes/web.php`
- Verifique se o Keycloak está configurado

### Layout quebrado em mobile?
- Teste em modo responsivo (F12 → Toggle device toolbar)
- Verifique as media queries

---

Divirta-se personalizando! 🎉

