<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Task Controller') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /* Embed Tailwind CSS basics */
            * { margin: 0; padding: 0; box-sizing: border-box; }
            html, body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; line-height: 1.5; }
            body { color: #1f2937; }
        </style>
    @endif

    <style>
        /* Custom animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4); }
            50% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            height: 100%;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1200px;
            animation: fadeIn 0.7s ease-out;
        }

        .login-container {
            display: flex;
            gap: 0;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .login-left {
            flex: 1;
            padding: 60px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: slideInLeft 0.8s ease-out;
        }

        .login-right {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: slideInRight 0.8s ease-out;
        }

        .logo-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 28px;
            margin-bottom: 24px;
            backdrop-filter: blur(10px);
        }

        .logo-text {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .logo-subtitle {
            font-size: 16px;
            opacity: 0.9;
        }

        .features-list {
            margin-top: 60px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .feature-item {
            display: flex;
            gap: 16px;
            animation: fadeIn 0.8s ease-out backwards;
        }

        .feature-item:nth-child(1) { animation-delay: 0.3s; }
        .feature-item:nth-child(2) { animation-delay: 0.4s; }
        .feature-item:nth-child(3) { animation-delay: 0.5s; }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 24px;
            backdrop-filter: blur(10px);
        }

        .feature-content h3 {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 4px 0;
        }

        .feature-content p {
            font-size: 12px;
            opacity: 0.8;
            margin: 0;
        }

        .login-form-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .form-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 14px;
            color: #6b7280;
        }

        .login-button {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.5s ease;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .login-button:hover::before {
            left: 100%;
        }

        .security-info {
            margin-top: 20px;
            padding: 16px;
            background: #f0f4f8;
            border-radius: 10px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border: 1px solid #e0e7ff;
        }

        .security-info strong {
            color: #667eea;
            display: block;
            margin-bottom: 4px;
        }

        .help-text {
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            margin-top: 16px;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-left {
                padding: 40px 24px;
                display: none;
            }

            .login-right {
                padding: 40px 24px;
            }

            body {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <!-- Left Side - Features (Hidden on mobile) -->
            <div class="login-left">
                <div>
                    <div class="logo-icon">✓</div>
                    <div class="logo-text">Task Controller</div>
                    <div class="logo-subtitle">Gerencie suas tarefas com eficiência</div>
                </div>

                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">📋</div>
                        <div class="feature-content">
                            <h3>Organização Inteligente</h3>
                            <p>Mantenha suas tarefas organizadas e priorizadas</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">👥</div>
                        <div class="feature-content">
                            <h3>Colaboração em Tempo Real</h3>
                            <p>Trabalhe em equipe com sincronização instantânea</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">🔐</div>
                        <div class="feature-content">
                            <h3>Segurança Avançada</h3>
                            <p>Seus dados protegidos com autenticação moderna</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="login-right">
                <div class="login-form-header">
                    <h2 class="form-title">Bem-vindo de volta</h2>
                    <p class="form-subtitle">Faça login para acessar seu painel</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1f2937; font-size: 14px;">
                            Email
                        </label>
                        <input type="email" name="email" placeholder="seu@email.com"
                               value="{{ old('email') }}"
                               style="width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px; font-family: inherit; transition: all 0.3s ease;"
                               onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                               onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none';"
                               required>
                    </div>

                    <!-- Password Field -->
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1f2937; font-size: 14px;">
                            Senha
                        </label>
                        <input type="password" name="password" placeholder="••••••••"
                               style="width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px; font-family: inherit; transition: all 0.3s ease;"
                               onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                               onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none';"
                               required>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
                            @foreach ($errors->all() as $error)
                                <p style="margin: 4px 0;">❌ {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <button type="submit" class="login-button">
                        🔑 Entrar
                    </button>
                </form>

                <div class="security-info">
                    <strong>🔒 Conexão Segura</strong>
                    Autenticação segura com Keycloak (OAuth 2.0)
                </div>

                <div class="help-text">
                    Credenciais padrão: admin / admin123 <br>
                    Ou peça ao administrador para criar sua conta
                </div>
            </div>
        </div>
    </div>
</body>
</html>

