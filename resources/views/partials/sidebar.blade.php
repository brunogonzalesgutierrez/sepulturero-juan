@php $user = auth()->user(); @endphp

{{-- DASHBOARD --}}
<a href="{{ route('dashboard') }}" class="sidebar-item">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<hr class="sidebar-divider">

{{-- MÓDULO USUARIOS --}}
@canany(['usuarios.ver','empleados.ver','roles.ver'])
<div class="sidebar-section-title">Usuarios</div>

@can('empleados.ver')
<a href="{{ route('empleados.index') }}" class="sidebar-item">
    <i class="bi bi-person-badge"></i> Empleados
</a>
@endcan

@can('usuarios.ver')
<a href="{{ route('usuarios.index') }}" class="sidebar-item">
    <i class="bi bi-people"></i> Usuarios
</a>
@endcan

@can('roles.ver')
<a href="{{ route('roles.index') }}" class="sidebar-item">
    <i class="bi bi-shield-lock"></i> Roles y Permisos
</a>
@endcan
@endcanany

<hr class="sidebar-divider">

{{-- MÓDULO INHUMACIONES --}}
@canany(['cementerios.ver','espacios.ver','inhumaciones.ver','mantenimientos.ver'])
<div class="sidebar-section-title">Cementerio</div>

@can('cementerios.ver')
<a href="{{ route('cementerios.index') }}" class="sidebar-item">
    <i class="bi bi-geo-alt"></i> Cementerios
</a>
<a href="{{ route('tipo_inhumaciones.index') }}" class="sidebar-item">
    <i class="bi bi-tags"></i> Tipos de Espacio
</a>
@endcan


@can('espacios.ver')
<a href="{{ route('espacios.index') }}" class="sidebar-item">
    <i class="bi bi-grid-3x3-gap"></i> Espacios
</a>
@endcan

@can('inhumaciones.ver')
<a href="{{ route('inhumaciones.index') }}" class="sidebar-item">
    <i class="bi bi-flower1"></i> Inhumaciones
</a>
@endcan

@can('mantenimientos.ver')
<a href="{{ route('mantenimientos.index') }}" class="sidebar-item">
    <i class="bi bi-tools"></i> Mantenimientos
</a>
@endcan
@endcanany

<hr class="sidebar-divider">

{{-- MÓDULO VENTAS --}}
@canany(['clientes.ver','contratos.ver','ventas.ver'])
<div class="sidebar-section-title">Ventas</div>

@can('clientes.ver')
<a href="{{ route('clientes.index') }}" class="sidebar-item">
    <i class="bi bi-person-lines-fill"></i> Clientes
</a>
@endcan

@can('contratos.ver')
<a href="{{ route('contratos.index') }}" class="sidebar-item">
    <i class="bi bi-file-earmark-text"></i> Contratos
</a>
@endcan

@can('ventas.ver')
<a href="{{ route('ventas.index') }}" class="sidebar-item">
    <i class="bi bi-cart-check"></i> Ventas
</a>
@endcan
@endcanany

<hr class="sidebar-divider">

{{-- MÓDULO PAGOS --}}
@can('pagos.ver')
<div class="sidebar-section-title">Pagos</div>
<a href="{{ route('pagos.index') }}" class="sidebar-item">
    <i class="bi bi-cash-coin"></i> Gestión de Pagos
</a>
@endcan

<hr class="sidebar-divider">

{{-- MÓDULO REPORTES --}}
@can('reportes.ver')
<div class="sidebar-section-title">Reportes</div>
<a href="{{ route('reportes.index') }}" class="sidebar-item">
    <i class="bi bi-bar-chart-line"></i> Reportes
</a>
<a href="{{ route('reportes.ventas') }}" class="sidebar-item" style="padding-left:2rem; font-size:0.82rem;">
    <i class="bi bi-cart-check"></i> Ventas
</a>
<a href="{{ route('reportes.pagos') }}" class="sidebar-item" style="padding-left:2rem; font-size:0.82rem;">
    <i class="bi bi-cash-coin"></i> Pagos
</a>
<a href="{{ route('reportes.espacios') }}" class="sidebar-item" style="padding-left:2rem; font-size:0.82rem;">
    <i class="bi bi-grid-3x3-gap"></i> Espacios
</a>
<a href="{{ route('reportes.contratos') }}" class="sidebar-item" style="padding-left:2rem; font-size:0.82rem;">
    <i class="bi bi-file-earmark-text"></i> Contratos
</a>
@endcan

@can('bitacora.ver')
<a href="{{ route('bitacora.index') }}" class="sidebar-item">
    <i class="bi bi-journal-text"></i> Bitácora
</a>
@endcan

<hr class="sidebar-divider">

{{-- PERFIL --}}
<div class="sidebar-section-title">Mi cuenta</div>
<a href="{{ route('perfil.index') }}" class="sidebar-item">
    <i class="bi bi-person-circle"></i> Mi Perfil
</a>