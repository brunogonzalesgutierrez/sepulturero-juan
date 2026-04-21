@extends('layouts.app')
@section('title', 'Reportes')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-bar-chart-line me-2"></i>Reportes</h1>
</div>

<div class="row g-3">
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('reportes.ventas') }}" class="text-decoration-none">
            <div class="stat-card text-center py-4" style="cursor:pointer;">
                <i class="bi bi-cart-check" style="font-size:2.5rem; color:var(--color-accent);"></i>
                <div class="stat-label mt-2">Reporte de Ventas</div>
                <small class="text-muted">Por período, tipo y moneda</small>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('reportes.pagos') }}" class="text-decoration-none">
            <div class="stat-card text-center py-4" style="cursor:pointer;">
                <i class="bi bi-cash-coin" style="font-size:2.5rem; color:#198754;"></i>
                <div class="stat-label mt-2">Reporte de Pagos</div>
                <small class="text-muted">Cobranzas y cuotas vencidas</small>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('reportes.espacios') }}" class="text-decoration-none">
            <div class="stat-card text-center py-4" style="cursor:pointer;">
                <i class="bi bi-grid-3x3-gap" style="font-size:2.5rem; color:#0dcaf0;"></i>
                <div class="stat-label mt-2">Estado de Espacios</div>
                <small class="text-muted">Disponibles, ocupados, mantenimiento</small>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('reportes.contratos') }}" class="text-decoration-none">
            <div class="stat-card text-center py-4" style="cursor:pointer;">
                <i class="bi bi-file-earmark-text" style="font-size:2.5rem; color:#dc3545;"></i>
                <div class="stat-label mt-2">Contratos y Saldos</div>
                <small class="text-muted">Saldos pendientes por contrato</small>
            </div>
        </a>
    </div>
</div>
@endsection