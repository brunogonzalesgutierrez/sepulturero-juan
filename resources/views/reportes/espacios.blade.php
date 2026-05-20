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
                    <option value="{{ $c->id }}" {{ $cementerioId == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8">
                <div class="d-flex gap-2 align-items-end flex-wrap">
                    <button class="btn btn-sm btn-primary"><i class="bi bi-search me-1"></i>Filtrar</button>
                    @can('reportes.exportar')
                    <button name="exportar" value="pdf" class="btn btn-sm btn-danger">
                        <i class="bi bi-file-pdf me-1"></i>Descargar PDF
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEnviar">
                        <i class="bi bi-envelope me-1"></i>Enviar PDF
                    </button>
                    @endcan
                    <a href="{{ route('reportes.espacios') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

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
                        <td><span class="badge badge-{{ $e->estado }}">{{ ucfirst($e->estado) }}</span></td>
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
    new Chart(document.getElementById('estadoChart'), {
        type: 'pie',
        data: {
            labels: @json($porEstado->keys()->map(fn($k) => ucfirst($k))),
            datasets: [{ data: @json($porEstado->values()), backgroundColor: ['#198754','#dc3545','#ffc107','#0dcaf0'] }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('tipoChart'), {
        type: 'bar',
        data: {
            labels: @json($porTipo->keys()),
            datasets: [{ label: 'Espacios', data: @json($porTipo->values()), backgroundColor: 'rgba(201,168,76,0.7)', borderColor: '#c9a84c', borderWidth: 2, borderRadius: 4 }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
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
        const form = document.getElementById('formEspacios');
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