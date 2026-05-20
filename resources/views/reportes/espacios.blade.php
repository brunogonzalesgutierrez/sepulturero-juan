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
        <form method="GET" id="formEspacios" class="row g-2 align-items-end">
            <input type="hidden" name="correo_destino" id="correo_destino_input">

            <div class="col-md-4">
                <select name="cementerio_id" class="form-select form-select-sm">
                    <option value="">Todos los cementerios</option>
                    @foreach($cementerios as $c)
                    <option value="{{ $c->id }}" {{ $cementerioId == $c->id ? 'selected' : '' }}>
                        {{ $c->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8">
                <div class="d-flex gap-2 align-items-end flex-wrap">

                    {{-- Filtrar --}}
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>

                    @can('reportes.exportar')

                    {{-- Descargar PDF --}}
                    <button name="exportar" value="pdf" class="btn btn-sm btn-danger">
                        <i class="bi bi-file-pdf me-1"></i>Descargar PDF
                    </button>

                    {{-- Enviar PDF --}}
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEnviar"
                    >
                        <i class="bi bi-envelope me-1"></i>Enviar PDF
                    </button>

                    @endcan

                    {{-- Limpiar --}}
                    <a href="{{ route('reportes.espacios') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>

                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tarjetas resumen --}}
<div class="row g-3 mb-3">
    @foreach($porEstado as $estado => $cantidad)
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold">{{ $cantidad }}</div>
                <div class="text-muted small">{{ ucfirst($estado) }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Gráficas --}}
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-pie-chart me-1"></i>Por Estado</div>
            <div class="card-body">
                <canvas id="estadoChart" height="160"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-bar-chart me-1"></i>Por Tipo</div>
            <div class="card-body">
                <canvas id="tipoChart" height="160"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Tabla --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cementerio</th>
                        <th>Tipo</th>
                        <th>Ancho (m)</th>
                        <th>Largo (m)</th>
                        <th>Área (m²)</th>
                        <th>Estado</th>
                        <th>Precio m²</th>
                        <th>Cap. Máx.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($espacios as $e)
                    <tr>
                        <td class="text-muted">{{ $e->id }}</td>
                        <td>{{ $e->cementerio->nombre }}</td>
                        <td>{{ $e->tipoInhumacion->nombre }}</td>
                        <td>{{ number_format($e->dimension->ancho, 2) }}</td>
                        <td>{{ number_format($e->dimension->largo, 2) }}</td>
                        <td>{{ number_format($e->dimension->area, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $e->estado }}">
                                {{ ucfirst($e->estado) }}
                            </span>
                        </td>
                        <td>{{ number_format($e->tipoInhumacion->precio_m2, 2) }}</td>
                        <td>{{ $e->tipoInhumacion->capacidad_max }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No hay espacios registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Enviar PDF --}}
@can('reportes.exportar')
<div class="modal fade" id="modalEnviar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-envelope me-2"></i>Enviar Reporte PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                @if($usuariosActivos->count() > 0)
                <p class="fw-bold small mb-1">Usuarios del sistema:</p>
                <div class="border rounded p-2 mb-3" style="max-height:180px; overflow-y:auto;">
                    @foreach($usuariosActivos as $u)
                    <div class="form-check">
                        <input
                            class="form-check-input destinatario-check"
                            type="checkbox"
                            value="{{ $u->email }}"
                            id="dest_{{ $u->id }}"
                        >
                        <label class="form-check-label small" for="dest_{{ $u->id }}">
                            {{ $u->empleado->nombre }} {{ $u->empleado->paterno }}
                            <span class="text-muted">&lt;{{ $u->email }}&gt;</span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @endif

                <p class="fw-bold small mb-1">Correos externos adicionales:</p>
                <input
                    type="text"
                    id="correos_externos"
                    class="form-control form-control-sm"
                    placeholder="correo1@ejemplo.com, correo2@ejemplo.com"
                >
                <small class="text-muted">Separa múltiples correos con coma.</small>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="enviarReporte()">
                    <i class="bi bi-send me-1"></i>Enviar
                </button>
            </div>
        </div>
    </div>
</div>
@endcan

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('estadoChart'), {
        type: 'pie',
        data: {
            labels: @json($porEstado->keys()->map(fn($k) => ucfirst($k))),
            datasets: [{
                data: @json($porEstado->values()),
                backgroundColor: ['#198754', '#dc3545', '#ffc107', '#0dcaf0']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    new Chart(document.getElementById('tipoChart'), {
        type: 'bar',
        data: {
            labels: @json($porTipo->keys()),
            datasets: [{
                label: 'Espacios',
                data: @json($porTipo->values()),
                backgroundColor: 'rgba(201,168,76,0.7)',
                borderColor: '#c9a84c',
                borderWidth: 2,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    function enviarReporte() {
        const checks = [...document.querySelectorAll('.destinatario-check:checked')]
            .map(c => c.value);

        const externos = document.getElementById('correos_externos').value
            .split(',').map(e => e.trim()).filter(e => e);

        const todos = [...new Set([...checks, ...externos])];

        if (todos.length === 0) {
            alert('Selecciona al menos un destinatario.');
            return;
        }

        document.getElementById('correo_destino_input').value = todos.join(',');

        const form = document.getElementById('formEspacios');
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'exportar';
        input.value = 'pdf';
        form.appendChild(input);
        form.submit();
    }
</script>
@endpush