<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Controller - Rattes Factory</title>

    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- API Token for API calls -->
    <meta name="api-token" content="{{ session('keycloak_access_token') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #ec4899;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f8fafc;
            --dark-bg: #0f172a;
            --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-bottom: 60px;
        }

        /* Header Moderno */
        .modern-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.8rem 2rem;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 3px solid rgba(255, 255, 255, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-brand-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .header-brand-text h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            background: linear-gradient(135deg, #ffffff 0%, #f3f4f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-brand-text p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin: 0;
            font-weight: 300;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .user-details h3 {
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0;
        }

        .user-details p {
            font-size: 0.8rem;
            opacity: 0.8;
            margin: 0;
        }

        /* Badge de Role do Usuário - UX Profissional */
        .user-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.35rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .user-role-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .user-role-badge i {
            font-size: 0.9rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 72px;
            width: 280px;
            height: calc(100vh - 72px - 60px);
            background: white;
            border-right: 1px solid #e2e8f0;
            overflow-y: auto;
            z-index: 999;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.05);
            transition: left 0.3s ease;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1.5rem 0;
        }

        .sidebar-menu li {
            margin: 0.5rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            color: #475569;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
        }

        .sidebar-menu a:hover {
            background: #f1f5f9;
            color: var(--primary-color);
            padding-left: 2rem;
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%);
            color: var(--primary-color);
            border-right: 4px solid var(--primary-color);
            padding-left: 1.5rem;
        }

        .sidebar-menu a i {
            font-size: 1.3rem;
            width: 24px;
        }

        /* Main Content */
        .main-wrapper {
            display: flex;
            flex: 1;
            margin-top: 72px;
            margin-bottom: 0px;
            padding-top: 0px;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 0.5rem 2rem 2rem 2rem;
            background: var(--light-bg);
        }

        /* Footer */
        .modern-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
            z-index: 998;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-company {
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .footer-year {
            color: #94a3b8;
            font-size: 0.9rem;
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .card-modern {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }

        .card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .card-icon.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .card-icon.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .card-icon.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .card-icon.danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .card-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .card-label {
            font-size: 0.95rem;
            color: #64748b;
            margin-top: 0.5rem;
        }

        .card-footer {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            font-size: 0.9rem;
            color: #94a3b8;
        }

        /* Task List */
        .task-list {
            list-style: none;
            padding: 0;
        }

        .task-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.2rem;
            background: #f8fafc;
            border-radius: 10px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .task-item:hover {
            background: #f1f5f9;
            border-left-color: var(--primary-color);
        }

        .task-checkbox {
            width: 24px;
            height: 24px;
            border: 2px solid #cbd5e1;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            margin-top: 0.2rem;
            flex-shrink: 0;
        }

        .task-checkbox:hover {
            border-color: var(--primary-color);
        }

        .task-checkbox.checked {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .task-content {
            flex: 1;
        }

        .task-title {
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .task-status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .task-status.planning {
            background: #dbeafe;
            color: #0369a1;
        }

        .task-status.in-progress {
            background: #fef3c7;
            color: #92400e;
        }

        .task-status.done {
            background: #dcfce7;
            color: #166534;
        }

        .task-status.paused {
            background: #f3f4f6;
            color: #4b5563;
        }

        .task-status.cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            margin-right: 1rem;
            transition: all 0.3s ease;
        }

        .mobile-menu-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
            }

            .sidebar {
                position: fixed;
                left: -280px;
                transition: left 0.3s ease;
                height: calc(100vh - 72px - 60px);
                top: 72px;
            }

            .sidebar.active {
                left: 0 !important;
            }

            .main-content {
                margin-left: 0;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .header-brand-icon {
                display: none;
            }

            .header-brand-text h1 {
                font-size: 1.5rem;
            }

            .header-user {
                gap: 1rem;
            }

            .user-details {
                display: none;
            }
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>
    <!-- Header Moderno -->
    @include('partials.header')

    <!-- Main Wrapper com Sidebar e Content -->
    <div class="main-wrapper">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });

            // Mobile sidebar toggle
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.querySelector('.sidebar');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    sidebar.classList.toggle('active');
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768 &&
                        sidebar.classList.contains('active') &&
                        !sidebar.contains(e.target) &&
                        !toggleBtn.contains(e.target)) {
                        sidebar.classList.remove('active');
                    }
                });
            }
        });
    </script>
</body>
</html>
