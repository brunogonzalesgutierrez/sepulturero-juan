@extends('layouts.app')
@section('title', 'Reporte de Pagos')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-cash-coin me-2"></i>Reporte de Pagos</h1>
    <a href="{{ route('reportes.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Reportes
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" id="formPagos" class="row g-2 align-items-end">
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
                <label class="form-label form-label-sm">Método</label>
                <select name="metodo_pago" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    @foreach(['efectivo','transferencia','tarjeta','qr'] as $mp)
                    <option value="{{ $mp }}" {{ $metodo==$mp ? 'selected':'' }}>{{ ucfirst($mp) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
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
                    <a href="{{ route('reportes.pagos') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>

                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-5">
        <div class="stat-card">
            <div class="stat-number" style="font-size:1.6rem; color:#198754;">
                {{ number_format($totalCobrado, 2) }}
            </div>
            <div class="stat-label">Total Cobrado en el período</div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header py-2"><i class="bi bi-pie-chart me-1"></i>Por Método de Pago</div>
            <div class="card-body p-2">
                <canvas id="metodosChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header py-2"><i class="bi bi-list-check me-1"></i>Pagos del período</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Cuota</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Comprobante</th>
                        <th>Cobrador</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagos as $p)
                    @php $cliente = $p->cuota->planPago->pagoCredito->venta->cliente; @endphp
                    <tr>
                        <td class="text-muted">{{ $p->id }}</td>
                        <td>{{ $p->fecha_pago->format('d/m/Y') }}</td>
                        <td>{{ $cliente->nombre }} {{ $cliente->paterno }}</td>
                        <td>#{{ $p->cuota->nro_cuota }}</td>
                        <td><strong>{{ number_format($p->monto_pagado, 2) }}</strong></td>
                        <td>{{ ucfirst($p->metodo_pago) }}</td>
                        <td>{{ $p->comprobante ?? '—' }}</td>
                        <td>{{ $p->empleado->nombre }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No hay pagos en el período.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header py-2 text-danger">
        <i class="bi bi-exclamation-triangle me-1"></i>Cuotas Vencidas ({{ $cuotasVencidas->count() }})
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Cuota</th>
                        <th>Venció</th>
                        <th>Monto</th>
                        <th>Días Vencida</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuotasVencidas as $c)
                    @php $cliente = $c->planPago->pagoCredito->venta->cliente; @endphp
                    <tr class="table-danger">
                        <td>{{ $cliente->nombre }} {{ $cliente->paterno }} (CI: {{ $cliente->ci }})</td>
                        <td>#{{ $c->nro_cuota }}</td>
                        <td>{{ $c->fecha_vencimiento->format('d/m/Y') }}</td>
                        <td>{{ number_format($c->monto, 2) }}</td>
                        <td><strong>{{ $c->fecha_vencimiento->diffInDays(now()) }} días</strong></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-success py-3">No hay cuotas vencidas.</td>
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
    new Chart(document.getElementById('metodosChart'), {
        type: 'doughnut',
        data: {
            labels: @json($pagosPorMetodo->keys()->map(fn($k) => ucfirst($k))),
            datasets: [{
                data: @json($pagosPorMetodo->values()),
                backgroundColor: ['#198754', '#0dcaf0', '#ffc107', '#6f42c1'],
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'right' } }
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

        const form = document.getElementById('formPagos');
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'exportar';
        input.value = 'pdf';
        form.appendChild(input);
        form.submit();
    }
</script>
@endpush