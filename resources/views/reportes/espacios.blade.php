@extends('layouts.app')
@section('title', 'Reporte de Espacios')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-grid-3x3-gap me-2"></i>Estado de Espacios</h1>
    <a href="{{ route('reportes.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Reportes
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <select name="cementerio_id" class="form-select form-select-sm">
                    <option value="">Todos los cementerios</option>
                    @foreach($cementerios as $c)
                    <option value="{{ $c->id }}" {{ $cementerioId==$c->id ? 'selected':'' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8 d-flex gap-2">
                <button class="btn btn-sm btn-primary"><i class="bi bi-search me-1"></i>Filtrar</button>
                {{-- @can('reportes.exportar') --}}
                <button name="exportar" value="pdf" class="btn btn-sm btn-danger">
                    <i class="bi bi-file-pdf me-1"></i>PDF
                </button>
                {{-- @endcan --}}
                <a href="{{ route('reportes.espacios') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-pie-chart me-1"></i>Por Estado</div>
            <div class="card-body"><canvas id="estadoChart" height="160"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-bar-chart me-1"></i>Por Tipo</div>
            <div class="card-body"><canvas id="tipoChart" height="160"></canvas></div>
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
                        <th>Cementerio</th>
                        <th>Tipo</th>
                        <th>Sección</th>
                        <th>Fila/Nro</th>
                        <th>Estado</th>
                        <th>Precio m²</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($espacios as $e)
                    <tr>
                        <td class="text-muted">{{ $e->id }}</td>
                        <td>{{ $e->cementerio->nombre }}</td>
                        <td>{{ $e->tipoInhumacion->nombre }}</td>
                        <td>{{ $e->direccion->seccion ?? '—' }}</td>
                        <td>{{ $e->direccion->fila ?? '—' }} / {{ $e->direccion->numero ?? '—' }}</td>
                        <td><span class="badge badge-{{ $e->estado }}">{{ ucfirst($e->estado) }}</span></td>
                        <td>{{ number_format($e->precio_m2, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No hay espacios.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('estadoChart'), {
        type: 'pie',
        data: {
            labels: @json($porEstado -> keys() -> map(fn($k) => ucfirst($k))),
            datasets: [{
                data: @json($porEstado -> values()),
                backgroundColor: ['#198754', '#dc3545', '#ffc107', '#0dcaf0']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    new Chart(document.getElementById('tipoChart'), {
        type: 'bar',
        data: {
            labels: @json($porTipo -> keys()),
            datasets: [{
                label: 'Espacios',
                data: @json($porTipo -> values()),
                backgroundColor: 'rgba(201,168,76,0.7)',
                borderColor: '#c9a84c',
                borderWidth: 2,
                borderRadius: 4
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
</script>
@endpush