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
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                    @can('reportes.exportar')
                    <button name="exportar" value="pdf" class="btn btn-sm btn-danger">
                        <i class="bi bi-file-pdf me-1"></i>Descargar PDF
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEnviar">
                        <i class="bi bi-envelope me-1"></i>Enviar PDF
                    </button>
                    @endcan
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

@can('reportes.exportar')
<div class="modal fade" id="modalEnviar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-send me-2"></i>Enviar Reporte PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
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
                            @php $iniciales = strtoupper(substr($u->empleado->nombre,0,1).substr($u->empleado->paterno,0,1)); @endphp
                            <tr id="fila_{{ $u->id }}">
                                <td>
                                    <div style="width:34px;height:34px;border-radius:50%;background:#1a1a2e;color:#c9a84c;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;">{{ $iniciales }}</div>
                                </td>
                                <td>{{ $u->empleado->nombre }} {{ $u->empleado->paterno }}</td>
                                <td class="text-muted small">{{ $u->email }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-seleccionar"
                                        data-email="{{ $u->email }}" data-uid="{{ $u->id }}" onclick="toggleDestinatario(this)">
                                        <i class="bi bi-circle me-1"></i>Seleccionar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                <p class="fw-bold small mb-1">Correos externos adicionales:</p>
                <input type="text" id="correos_externos" class="form-control form-control-sm mb-1" placeholder="correo1@ejemplo.com, correo2@ejemplo.com">
                <small class="text-muted">Separa múltiples correos con coma.</small>
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
    new Chart(document.getElementById('metodosChart'), {
        type: 'doughnut',
        data: {
            labels: @json($pagosPorMetodo->keys()->map(fn($k) => ucfirst($k))),
            datasets: [{ data: @json($pagosPorMetodo->values()), backgroundColor: ['#198754','#0dcaf0','#ffc107','#6f42c1'] }]
        },
        options: { responsive: true, plugins: { legend: { position: 'right' } } }
    });

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
        const externos = document.getElementById('correos_externos').value.split(',').map(e => e.trim()).filter(e => e);
        const todos    = [...new Set([...seleccionados, ...externos])];
        const resumen  = document.getElementById('resumen_destinatarios');
        const lista    = document.getElementById('lista_seleccionados');
        if (todos.length > 0) { lista.textContent = todos.join(', '); resumen.classList.remove('d-none'); }
        else { resumen.classList.add('d-none'); }
    }

    document.getElementById('correos_externos')?.addEventListener('input', actualizarResumen);

    function enviarReporte() {
        const externos = document.getElementById('correos_externos').value.split(',').map(e => e.trim()).filter(e => e);
        const todos    = [...new Set([...seleccionados, ...externos])];
        if (todos.length === 0) { alert('Selecciona al menos un destinatario.'); return; }
        document.getElementById('correo_destino_input').value = todos.join(',');
        const form = document.getElementById('formPagos');
        const input = document.createElement('input');
        input.type = 'hidden'; input.name = 'exportar'; input.value = 'pdf';
        form.appendChild(input);
        form.submit();
    }

    document.getElementById('modalEnviar').addEventListener('show.bs.modal', function () {
        seleccionados.clear();
        document.querySelectorAll('.btn-seleccionar').forEach(btn => {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
            btn.innerHTML = '<i class="bi bi-circle me-1"></i>Seleccionar';
        });
        document.querySelectorAll('tr[id^="fila_"]').forEach(tr => tr.classList.remove('table-success'));
        document.getElementById('correos_externos').value = '';
        document.getElementById('resumen_destinatarios').classList.add('d-none');
    });

    document.getElementById('modalEnviar').addEventListener('shown.bs.modal', function () {
        this.scrollTop = 0;
    });
</script>
@endpush