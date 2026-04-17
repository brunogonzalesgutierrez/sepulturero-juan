@extends('layouts.app')
@section('title', 'Gestión de Pagos')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-cash-coin me-2"></i>Gestión de Pagos</h1>
    <div class="d-flex gap-2">
        @can('pagos.editar')
        <form method="POST" action="{{ route('pagos.marcarVencidas') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-warning"
                onclick="return confirm('¿Marcar como vencidas todas las cuotas con fecha pasada?')">
                <i class="bi bi-exclamation-triangle me-1"></i>Marcar Vencidas
            </button>
        </form>
        @endcan
        @can('pagos.crear')
        <a href="{{ route('pagos.create') }}" class="btn btn-gold">
            <i class="bi bi-plus-lg me-1"></i>Registrar Pago
        </a>
        @endcan
    </div>
</div>

{{-- Resumen cuotas --}}
<div class="row g-2 mb-3">
    @php
    $totalPendientes = \App\Models\Cuota::where('estado','pendiente')->count();
    $totalVencidas = \App\Models\Cuota::where('estado','vencida')->count();
    $totalPagadas = \App\Models\Cuota::where('estado','pagada')->count();
    $totalCobrado = \App\Models\Pago::whereMonth('fecha_pago', now()->month)->sum('monto_pagado');
    @endphp
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="stat-number">{{ $totalPendientes }}</div>
                    <div class="stat-label">Cuotas Pendientes</div>
                </div>
                <i class="bi bi-hourglass stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#dc3545;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="stat-number" style="color:#dc3545;">{{ $totalVencidas }}</div>
                    <div class="stat-label">Cuotas Vencidas</div>
                </div>
                <i class="bi bi-exclamation-circle stat-icon" style="color:#dc3545;"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#198754;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="stat-number" style="color:#198754;">{{ $totalPagadas }}</div>
                    <div class="stat-label">Cuotas Pagadas</div>
                </div>
                <i class="bi bi-check-circle stat-icon" style="color:#198754;"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#c9a84c;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="stat-number" style="font-size:1.3rem;">{{ number_format($totalCobrado, 0) }}</div>
                    <div class="stat-label">Cobrado este mes</div>
                </div>
                <i class="bi bi-currency-dollar stat-icon"></i>
            </div>
        </div>
    </div>
</div>

{{-- Filtros --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="buscar" class="form-control form-control-sm"
                    placeholder="CI, nombre o apellido del cliente..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2">
                <select name="metodo_pago" class="form-select form-select-sm">
                    <option value="">Método</option>
                    @foreach(['efectivo','transferencia','tarjeta','qr'] as $mp)
                    <option value="{{ $mp }}" {{ request('metodo_pago') == $mp ? 'selected':'' }}>{{ ucfirst($mp) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5 d-flex gap-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button>
                <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabla pagos registrados --}}
<div class="card mb-4">
    <div class="card-header py-2"><i class="bi bi-list-check me-1"></i>Pagos Registrados</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Cuota</th>
                        <th>F. Pago</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Comprobante</th>
                        <th>Cobrador</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagos as $p)
                    @php
                    $cliente = $p->cuota->planPago->pagoCredito->venta->cliente;
                    @endphp
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $p->id }}</td>
                        <td>
                            <strong>{{ $cliente->nombre }} {{ $cliente->paterno }}</strong><br>
                            <small class="text-muted">CI: {{ $cliente->ci }}</small>
                        </td>
                        <td>Cuota #{{ $p->cuota->nro_cuota }}</td>
                        <td>{{ $p->fecha_pago->format('d/m/Y') }}</td>
                        <td><strong>{{ number_format($p->monto_pagado, 2) }}</strong></td>
                        <td><span class="badge bg-secondary">{{ ucfirst($p->metodo_pago) }}</span></td>
                        <td>{{ $p->comprobante ?? '—' }}</td>
                        <td>{{ $p->empleado->nombre }} {{ $p->empleado->paterno }}</td>
                        <td class="text-center">
                            <a href="{{ route('pagos.show', $p) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('pagos.editar')
                            <a href="{{ route('pagos.edit', $p) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>No hay pagos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pagos->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">{{ $pagos->firstItem() }}–{{ $pagos->lastItem() }} de {{ $pagos->total() }}</small>
        {{ $pagos->links() }}
    </div>
    @endif
</div>

{{-- Cuotas pendientes y vencidas --}}
<div class="card">
    <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <span><i class="bi bi-hourglass-split me-1"></i>Cuotas Pendientes / Vencidas</span>
        <span class="badge bg-warning text-dark">{{ $cuotasPendientes->count() }} cuotas</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height:400px; overflow-y:auto;">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Cuota</th>
                        <th>Vencimiento</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuotasPendientes as $cuota)
                    @php $cliente = $cuota->planPago->pagoCredito->venta->cliente; @endphp
                    <tr class="{{ $cuota->estado == 'vencida' ? 'table-danger' : '' }}">
                        <td>
                            <strong>{{ $cliente->nombre }} {{ $cliente->paterno }}</strong><br>
                            <small>CI: {{ $cliente->ci }}</small>
                        </td>
                        <td>#{{ $cuota->nro_cuota }}</td>
                        <td>
                            {{ $cuota->fecha_vencimiento->format('d/m/Y') }}
                            @if($cuota->fecha_vencimiento->isPast())
                            <br><small class="text-danger">
                                {{ $cuota->fecha_vencimiento->diffForHumans() }}
                            </small>
                            @endif
                        </td>
                        <td>{{ number_format($cuota->monto, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $cuota->estado }}">
                                {{ ucfirst($cuota->estado) }}
                            </span>
                        </td>
                        <td>
                            @can('pagos.crear')
                            <a href="{{ route('pagos.create') }}?cuota_id={{ $cuota->id }}"
                                class="btn btn-sm btn-gold py-0 px-2">
                                <i class="bi bi-cash me-1"></i>Cobrar
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">No hay cuotas pendientes.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection