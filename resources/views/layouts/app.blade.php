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
    
    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    
    
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app-blade.css') }}">

   
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn-toggle-sidebar" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <a href="{{ route('home') }}" class="navbar-brand-custom">
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


    <footer class="footer mt-auto py-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="text-muted small">
                © {{ date('Y') }} El Sepulturero Juan
            </span>
            <x-contador-visitas />
        </div>
    </footer>
</body>

</html>