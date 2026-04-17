<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'El Sepulturero Juan')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --navbar-height: 56px;
            --color-primary: #1a1a2e;
            --color-secondary: #16213e;
            --color-accent: #c9a84c;
            --color-accent-hover: #e8c96a;
            --color-text-light: #e0d6c8;
            --color-text-muted: #8a8a9a;
            --color-bg: #f4f1eb;
            --color-card: #ffffff;
            --color-border: #e0d6c8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
            background-color: var(--color-bg);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-height);
            background: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.2rem;
            z-index: 1100;
            border-bottom: 2px solid var(--color-accent);
        }

        .navbar-brand-custom {
            font-family: 'Cinzel', serif;
            color: var(--color-accent);
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand-custom:hover {
            color: var(--color-accent-hover);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-user {
            color: var(--color-text-light);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .navbar-user .badge-role {
            background: var(--color-accent);
            color: var(--color-primary);
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 700;
        }

        .btn-logout {
            background: transparent;
            border: 1px solid var(--color-accent);
            color: var(--color-accent);
            font-size: 0.8rem;
            padding: 4px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: var(--color-accent);
            color: var(--color-primary);
        }

        .btn-toggle-sidebar {
            background: transparent;
            border: none;
            color: var(--color-text-light);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: color 0.2s;
        }

        .btn-toggle-sidebar:hover {
            color: var(--color-accent);
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: var(--color-secondary);
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1050;
            border-right: 1px solid rgba(201, 168, 76, 0.2);
        }

        .sidebar.collapsed {
            transform: translateX(calc(-1 * var(--sidebar-width)));
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--color-secondary);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--color-accent);
            border-radius: 2px;
        }

        .sidebar-section-title {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--color-text-muted);
            padding: 1.2rem 1.2rem 0.4rem;
            font-family: 'Cinzel', serif;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.6rem 1.2rem;
            color: var(--color-text-light);
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover {
            background: rgba(201, 168, 76, 0.1);
            color: var(--color-accent);
            border-left-color: var(--color-accent);
        }

        .sidebar-item.active {
            background: rgba(201, 168, 76, 0.15);
            color: var(--color-accent);
            border-left-color: var(--color-accent);
            font-weight: 700;
        }

        .sidebar-item i {
            font-size: 1rem;
            min-width: 20px;
        }

        .sidebar-divider {
            border: none;
            border-top: 1px solid rgba(201, 168, 76, 0.1);
            margin: 0.5rem 1rem;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 1.5rem;
            min-height: calc(100vh - var(--navbar-height));
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--color-accent);
        }

        .page-title {
            font-family: 'Cinzel', serif;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--color-primary);
            margin: 0;
        }

        /* ===== CARDS ===== */
        .card {
            border: 1px solid var(--color-border);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            background: var(--color-primary);
            color: var(--color-accent);
            font-family: 'Cinzel', serif;
            font-weight: 600;
            border-radius: 8px 8px 0 0 !important;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: var(--color-card);
            border-radius: 10px;
            padding: 1.2rem;
            border: 1px solid var(--color-border);
            border-left: 4px solid var(--color-accent);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .stat-card .stat-number {
            font-family: 'Cinzel', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-primary);
            line-height: 1;
        }

        .stat-card .stat-label {
            color: var(--color-text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        .stat-card .stat-icon {
            font-size: 2rem;
            color: var(--color-accent);
            opacity: 0.7;
        }

        /* ===== TABLES ===== */
        .table thead th {
            background: var(--color-primary);
            color: var(--color-accent);
            font-family: 'Cinzel', serif;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            border: none;
        }

        .table tbody tr:hover {
            background: rgba(201, 168, 76, 0.05);
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: var(--color-primary);
            border-color: var(--color-primary);
        }

        .btn-primary:hover {
            background: var(--color-accent);
            border-color: var(--color-accent);
            color: var(--color-primary);
        }

        .btn-gold {
            background: var(--color-accent);
            border-color: var(--color-accent);
            color: var(--color-primary);
            font-weight: 700;
        }

        .btn-gold:hover {
            background: var(--color-accent-hover);
            border-color: var(--color-accent-hover);
            color: var(--color-primary);
        }

        /* ===== ALERTS ===== */
        .alert {
            border-radius: 6px;
            font-size: 0.9rem;
        }

        /* ===== BADGES ESTADO ===== */
        .badge-disponible {
            background: #198754;
        }

        .badge-ocupado {
            background: #dc3545;
        }

        .badge-mantenimiento {
            background: #ffc107;
            color: #000;
        }

        .badge-reservado {
            background: #0dcaf0;
            color: #000;
        }

        .badge-activo {
            background: #198754;
        }

        .badge-inactivo {
            background: #6c757d;
        }

        .badge-pagado {
            background: #198754;
        }

        .badge-vencido {
            background: #dc3545;
        }

        .badge-pendiente {
            background: #ffc107;
            color: #000;
        }

        .badge-pagada {
            background: #198754;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(calc(-1 * var(--sidebar-width)));
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn-toggle-sidebar" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <a href="{{ route('dashboard') }}" class="navbar-brand-custom">
                <i class="bi bi-building"></i>
                El Sepulturero Juan
            </a>
        </div>
        <div class="navbar-right">
            <div class="navbar-user">
                <i class="bi bi-person-circle"></i>
                {{ auth()->user()->username }}
                <span class="badge-role">{{ auth()->user()->getRoleNames()->first() }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </button>
            </form>
        </div>
    </nav>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        @include('partials.sidebar')
    </aside>

    {{-- OVERLAY mobile --}}
    <div id="sidebarOverlay" onclick="closeSidebar()"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1040;"></div>

    {{-- MAIN --}}
    <main class="main-content" id="mainContent">

        {{-- Alertas flash --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const overlay = document.getElementById('sidebarOverlay');
        let collapsed = false;

        document.getElementById('sidebarToggle').addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-open');
                overlay.style.display = sidebar.classList.contains('mobile-open') ? 'block' : 'none';
            } else {
                collapsed = !collapsed;
                sidebar.classList.toggle('collapsed', collapsed);
                mainContent.classList.toggle('expanded', collapsed);
            }
        });

        function closeSidebar() {
            sidebar.classList.remove('mobile-open');
            overlay.style.display = 'none';
        }

        // Marcar item activo
        document.querySelectorAll('.sidebar-item').forEach(item => {
            if (item.href === window.location.href) {
                item.classList.add('active');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>