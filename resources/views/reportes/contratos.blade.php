@extends('layouts.app')
@section('title', 'Reporte de Contratos')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-file-earmark-text me-2"></i>Contratos y Saldos</h1>
    <a href="{{ route('reportes.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Reportes
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <select name="estado" class="form-select form-select-sm">
                    @foreach(['activo','pagado','vencido','cancelado'] as $est)
                    <option value="{{ $est }}" {{ $estado==$est ? 'selected':'' }}>{{ ucfirst($est) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="moneda" class="form-select form-select-sm">
                    <option value="">Moneda</option>
                    <option value="BOB" {{ $moneda=='BOB' ? 'selected':'' }}>BOB</option>
                    <option value="USD" {{ $moneda=='USD' ? 'selected':'' }}>USD</option>
                </select>
            </div>
            <div class="col-md-7 d-flex gap-2">
                <button class="btn btn-sm btn-primary"><i class="bi bi-search me-1"></i>Filtrar</button>
                @can('reportes.exportar')
                <button name="exportar" value="pdf" class="btn btn-sm btn-danger">
                    <i class="bi bi-file-pdf me-1"></i>PDF
                </button>
                @endcan
                <a href="{{ route('reportes.contratos') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-number">{{ $contratos->count() }}</div>
            <div class="stat-label">Contratos {{ ucfirst($estado) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-number" style="font-size:1.4rem;">{{ number_format($totalMonto, 2) }}</div>
            <div class="stat-label">Monto Total Contratos</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-left-color:#dc3545;">
            <div class="stat-number" style="font-size:1.4rem; color:#dc3545;">{{ number_format($totalSaldo, 2) }}</div>
            <div class="stat-label">Saldo Pendiente Total</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Espacio</th>
                        <th>Fecha</th>
                        <th>Monto Base</th>
                        <th>Saldo</th>
                        <th>Moneda</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contratos as $c)
                    <tr>
                        <td class="text-muted">{{ $c->id }}</td>
                        <td>{{ $c->cliente->nombre }} {{ $c->cliente->paterno }}<br><small>CI: {{ $c->cliente->ci }}</small></td>
                        <td>{{ $c->espacio->cementerio->nombre }}</td>
                        <td>{{ $c->fecha_contrato->format('d/m/Y') }}</td>
                        <td>{{ number_format($c->monto_base, 2) }}</td>
                        <td>
                            @if($c->saldo_pendiente > 0)
                            <span class="text-danger fw-bold">{{ number_format($c->saldo_pendiente, 2) }}</span>
                            @else
                            <span class="text-success">0.00</span>
                            @endif
                        </td>
                        <td>{{ $c->moneda }}</td>
                        <td><span class="badge badge-{{ $c->estado }}">{{ ucfirst($c->estado) }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No hay contratos.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection