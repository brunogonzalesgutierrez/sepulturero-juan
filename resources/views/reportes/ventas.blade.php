@extends('layouts.app')
@section('title', 'Reporte de Ventas')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-cart-check me-2"></i>Reporte de Ventas</h1>
    <a href="{{ route('reportes.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Reportes
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" id="formVentas" class="row g-2 align-items-end">
            <input type="hidden" name="correo_destino" id="correo_destino_input">

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
            <div class="col-md-4">
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
                    <a href="{{ route('reportes.ventas') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>

                </div>
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

{{-- Modal Enviar PDF --}}
@can('reportes.exportar')
<div class="modal fade" id="modalEnviar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-send me-2"></i>Enviar Reporte PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Tabla de usuarios --}}
                @if($usuariosActivos->count() > 0)
                <p class="fw-bold small mb-2">Usuarios del sistema:</p>
                <div class="table-responsive mb-3">
                    <table class="table table-hover table-sm mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:40px;"></th>
                                <th>Empleado</th>
                                <th>Correo</th>
                                <th style="width:100px;" class="text-center">Seleccionar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuariosActivos as $u)
                            @php
                                $iniciales = strtoupper(substr($u->empleado->nombre, 0, 1) . substr($u->empleado->paterno, 0, 1));
                            @endphp
                            <tr id="fila_{{ $u->id }}">
                                <td>
                                    <div style="
                                        width:34px; height:34px; border-radius:50%;
                                        background:#1a1a2e; color:#c9a84c;
                                        display:flex; align-items:center; justify-content:center;
                                        font-size:12px; font-weight:600;
                                    ">{{ $iniciales }}</div>
                                </td>
                                <td>{{ $u->empleado->nombre }} {{ $u->empleado->paterno }}</td>
                                <td class="text-muted small">{{ $u->email }}</td>
                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-secondary btn-seleccionar"
                                        data-email="{{ $u->email }}"
                                        data-uid="{{ $u->id }}"
                                        onclick="toggleDestinatario(this)"
                                    >
                                        <i class="bi bi-circle me-1"></i>Seleccionar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- Correos externos --}}
                <p class="fw-bold small mb-1">Correos externos adicionales:</p>
                <input
                    type="text"
                    id="correos_externos"
                    class="form-control form-control-sm mb-1"
                    placeholder="correo1@ejemplo.com, correo2@ejemplo.com"
                >
                <small class="text-muted">Separa múltiples correos con coma.</small>

                {{-- Resumen seleccionados --}}
                <div id="resumen_destinatarios" class="mt-3 p-2 rounded bg-light d-none">
                    <small class="text-muted">Destinatarios: <span id="lista_seleccionados" class="fw-bold text-dark"></span></small>
                </div>

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
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    @endif

    const seleccionados = new Set();

    function toggleDestinatario(btn) {
        const email = btn.dataset.email;
        const uid   = btn.dataset.uid;

        if (seleccionados.has(email)) {
            seleccionados.delete(email);
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
            btn.innerHTML = '<i class="bi bi-circle me-1"></i>Seleccionar';
            document.getElementById('fila_' + uid).classList.remove('table-success');
        } else {
            seleccionados.add(email);
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
            btn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Seleccionado';
            document.getElementById('fila_' + uid).classList.add('table-success');
        }

        actualizarResumen();
    }

    function actualizarResumen() {
        const externos = document.getElementById('correos_externos').value
            .split(',').map(e => e.trim()).filter(e => e);

        const todos = [...new Set([...seleccionados, ...externos])];
        const resumen = document.getElementById('resumen_destinatarios');
        const lista   = document.getElementById('lista_seleccionados');

        if (todos.length > 0) {
            lista.textContent = todos.join(', ');
            resumen.classList.remove('d-none');
        } else {
            resumen.classList.add('d-none');
        }
    }

    document.getElementById('correos_externos')
        ?.addEventListener('input', actualizarResumen);

    function enviarReporte() {
        const externos = document.getElementById('correos_externos').value
            .split(',').map(e => e.trim()).filter(e => e);

        const todos = [...new Set([...seleccionados, ...externos])];

        if (todos.length === 0) {
            alert('Selecciona al menos un destinatario.');
            return;
        }

        document.getElementById('correo_destino_input').value = todos.join(',');

        const form  = document.getElementById('formVentas');
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'exportar';
        input.value = 'pdf';
        form.appendChild(input);
        form.submit();
    }
</script>
@endpush