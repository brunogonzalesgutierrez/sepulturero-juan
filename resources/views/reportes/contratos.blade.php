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
        <form method="GET" id="formContratos" class="row g-2 align-items-end">
            <input type="hidden" name="correo_destino" id="correo_destino_input">

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
            <div class="col-md-7">
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
                    <a href="{{ route('reportes.contratos') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>

                </div>
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
                        <td>
                            {{ $c->cliente->nombre }} {{ $c->cliente->paterno }}
                            <br><small>CI: {{ $c->cliente->ci }}</small>
                        </td>
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
<script>
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

        const form = document.getElementById('formContratos');
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'exportar';
        input.value = 'pdf';
        form.appendChild(input);
        form.submit();
    }
</script>
@endpush