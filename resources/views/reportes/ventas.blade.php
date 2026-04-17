@extends('layouts.app')
@section('title', 'Reporte de Ventas')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-cart-check me-2"></i>Reporte de Ventas</h1>
    <a href="{{ route('reportes.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Reportes
    </a>
</div>

{{-- Filtros --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label form-label-sm">Desde</label>
                <input type="date" name="desde" class="form-control form-control-sm" value="{{ $desde }}">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Hasta</label>
                <input type="date" name="hasta" class="form-control form-control-sm" value="{{ $hasta }}">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Tipo</label>
                <select name="tipo_venta" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="contado" {{ $tipo=='contado' ? 'selected':'' }}>Contado</option>
                    <option value="credito" {{ $tipo=='credito' ? 'selected':'' }}>Crédito</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Moneda</label>
                <select name="moneda" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    <option value="BOB" {{ $moneda=='BOB' ? 'selected':'' }}>BOB</option>
                    <option value="USD" {{ $moneda=='USD' ? 'selected':'' }}>USD</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2 align-items-end">
                <button class="btn btn-sm btn-primary"><i class="bi bi-search me-1"></i>Filtrar</button>
                @can('reportes.exportar')
                <button name="exportar" value="pdf" class="btn btn-sm btn-danger">
                    <i class="bi bi-file-pdf me-1"></i>PDF
                </button>
                @endcan
                <a href="{{ route('reportes.ventas') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Resumen --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-number">{{ $ventas->count() }}</div>
            <div class="stat-label">Total Ventas</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-left-color:#198754;">
            <div class="stat-number" style="color:#198754; font-size:1.5rem;">
                {{ number_format($totalContado, 2) }}
            </div>
            <div class="stat-label">Contado</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-left-color:#ffc107;">
            <div class="stat-number" style="color:#e6a800; font-size:1.5rem;">
                {{ number_format($totalCredito, 2) }}
            </div>
            <div class="stat-label">Crédito</div>
        </div>
    </div>
</div>

{{-- Gráfica --}}
@if($ventasPorDia->count() > 0)
<div class="card mb-3">
    <div class="card-header py-2"><i class="bi bi-bar-chart me-1"></i>Ventas por día</div>
    <div class="card-body">
        <canvas id="ventasChart" height="80"></canvas>
    </div>
</div>
@endif

{{-- Tabla --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Espacio</th>
                        <th>Tipo</th>
                        <th>Total</th>
                        <th>Moneda</th>
                        <th>Vendedor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $v)
                    <tr>
                        <td class="text-muted">{{ $v->id }}</td>
                        <td>{{ $v->fecha_venta->format('d/m/Y') }}</td>
                        <td>{{ $v->cliente->nombre }} {{ $v->cliente->paterno }}</td>
                        <td>{{ $v->contrato->espacio->cementerio->nombre }}</td>
                        <td>
                            <span class="badge {{ $v->tipo_venta=='contado' ? 'bg-success':'bg-warning text-dark' }}">
                                {{ ucfirst($v->tipo_venta) }}
                            </span>
                        </td>
                        <td><strong>{{ number_format($v->precio_total, 2) }}</strong></td>
                        <td>{{ $v->moneda }}</td>
                        <td>{{ $v->empleado->nombre }} {{ $v->empleado->paterno }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No hay ventas en el período.</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($ventas->count() > 0)
                <tfoot>
                    <tr class="table-dark">
                        <th colspan="5" class="text-end">TOTAL:</th>
                        <th>{{ number_format($ventas->sum('precio_total'), 2) }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($ventasPorDia->count() > 0)
    new Chart(document.getElementById('ventasChart'), {
        type: 'bar',
        data: {
            labels: @json($ventasPorDia->keys()),
            datasets: [{
                label: 'Monto vendido',
                data: @json($ventasPorDia->values()),
                backgroundColor: 'rgba(201,168,76,0.7)',
                borderColor: '#c9a84c',
                borderWidth: 2,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif
</script>
@endpush