@extends('layouts.app')
@section('title', 'Nueva Venta')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-cart-plus me-2"></i>Nueva Venta</h1>
    <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<form method="POST" action="{{ route('ventas.store') }}" id="ventaForm">
    @csrf
    <div class="row g-3">
        {{-- Panel izquierdo --}}
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-header py-2">Datos generales</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Contrato <span class="text-danger">*</span></label>
                            <select name="contrato_id" id="contrato_id"
                                class="form-select @error('contrato_id') is-invalid @enderror" required>
                                <option value="">Seleccione un contrato activo sin venta...</option>
                                @foreach($contratos as $c)
                                <option value="{{ $c->id }}"
                                    data-cliente="{{ $c->cliente_id }}"
                                    data-monto="{{ $c->monto_base }}"
                                    data-moneda="{{ $c->moneda }}"
                                    {{ (old('contrato_id', request('contrato_id'))) == $c->id ? 'selected':'' }}>
                                    #{{ $c->id }} — {{ $c->cliente->nombre }} {{ $c->cliente->paterno }}
                                    (CI: {{ $c->cliente->ci }}) |
                                    {{ $c->espacio->cementerio->nombre }} — {{ number_format($c->monto_base, 2) }} {{ $c->moneda }}
                                </option>
                                @endforeach
                            </select>
                            @error('contrato_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Empleado Vendedor <span class="text-danger">*</span></label>
                            <select name="empleado_id" class="form-select @error('empleado_id') is-invalid @enderror" required>
                                <option value="">Seleccione...</option>
                                @foreach($empleados as $e)
                                <option value="{{ $e->id }}" {{ old('empleado_id', auth()->user()->empleado_id) == $e->id ? 'selected':'' }}>
                                    {{ $e->nombre }} {{ $e->paterno }}
                                </option>
                                @endforeach
                            </select>
                            @error('empleado_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fecha de Venta <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_venta"
                                class="form-control @error('fecha_venta') is-invalid @enderror"
                                value="{{ old('fecha_venta', now()->format('Y-m-d')) }}" required>
                            @error('fecha_venta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Precio Total <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="precio_total" id="precio_total"
                                class="form-control @error('precio_total') is-invalid @enderror"
                                value="{{ old('precio_total') }}" required>
                            @error('precio_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Moneda <span class="text-danger">*</span></label>
                            <select name="moneda" id="moneda" class="form-select @error('moneda') is-invalid @enderror" required>
                                <option value="BOB" {{ old('moneda','BOB') == 'BOB' ? 'selected':'' }}>BOB</option>
                                <option value="USD" {{ old('moneda') == 'USD' ? 'selected':'' }}>USD</option>
                            </select>
                            @error('moneda')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Cliente oculto --}}
                        <input type="hidden" name="cliente_id" id="cliente_id" value="{{ old('cliente_id') }}">
                    </div>
                </div>
            </div>

            {{-- Tipo de venta --}}
            <div class="card">
                <div class="card-header py-2">Tipo de Venta</div>
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="form-check form-check-inline w-100">
                                <input class="form-check-input" type="radio" name="tipo_venta"
                                    id="tipo_contado" value="contado"
                                    {{ old('tipo_venta','contado') == 'contado' ? 'checked':'' }}>
                                <label class="form-check-label fw-semibold" for="tipo_contado">
                                    <i class="bi bi-cash me-1"></i>Contado
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check form-check-inline w-100">
                                <input class="form-check-input" type="radio" name="tipo_venta"
                                    id="tipo_credito" value="credito"
                                    {{ old('tipo_venta') == 'credito' ? 'checked':'' }}>
                                <label class="form-check-label fw-semibold" for="tipo_credito">
                                    <i class="bi bi-calendar-check me-1"></i>Crédito
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Panel CONTADO --}}
                    <div id="panel_contado">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Método de Pago <span class="text-danger">*</span></label>
                                <select name="metodo_pago" class="form-select @error('metodo_pago') is-invalid @enderror">
                                    @foreach(['efectivo','transferencia','tarjeta','qr'] as $mp)
                                    <option value="{{ $mp }}" {{ old('metodo_pago') == $mp ? 'selected':'' }}>{{ ucfirst($mp) }}</option>
                                    @endforeach
                                </select>
                                @error('metodo_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Descuento</label>
                                <input type="number" step="0.01" name="descuento"
                                    class="form-control @error('descuento') is-invalid @enderror"
                                    value="{{ old('descuento', 0) }}" min="0">
                                @error('descuento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Panel CRÉDITO --}}
                    <div id="panel_credito" style="display:none;">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tasa de Interés (%) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="interes" id="interes"
                                    class="form-control @error('interes') is-invalid @enderror"
                                    value="{{ old('interes', 0) }}" min="0" max="100">
                                @error('interes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Frecuencia <span class="text-danger">*</span></label>
                                <select name="frecuencia" id="frecuencia" class="form-select @error('frecuencia') is-invalid @enderror">
                                    @foreach(['mensual','quincenal','semanal'] as $f)
                                    <option value="{{ $f }}" {{ old('frecuencia','mensual') == $f ? 'selected':'' }}>{{ ucfirst($f) }}</option>
                                    @endforeach
                                </select>
                                @error('frecuencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Nro. Cuotas <span class="text-danger">*</span></label>
                                <input type="number" name="nro_cuotas" id="nro_cuotas"
                                    class="form-control @error('nro_cuotas') is-invalid @enderror"
                                    value="{{ old('nro_cuotas', 12) }}" min="1" max="360">
                                @error('nro_cuotas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fecha Inicio Pagos <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio"
                                    class="form-control @error('fecha_inicio') is-invalid @enderror"
                                    value="{{ old('fecha_inicio', now()->addMonth()->format('Y-m-d')) }}">
                                @error('fecha_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fecha Fin Estimada <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_fin" id="fecha_fin"
                                    class="form-control @error('fecha_fin') is-invalid @enderror"
                                    value="{{ old('fecha_fin') }}">
                                @error('fecha_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel derecho — Resumen --}}
        <div class="col-md-5">
            <div class="card" style="position:sticky; top:70px;">
                <div class="card-header py-2"><i class="bi bi-receipt me-1"></i>Resumen de Venta</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th>Precio Base:</th>
                            <td id="res_precio">—</td>
                        </tr>
                        <tr id="row_interes" style="display:none;">
                            <th>Interés (%):</th>
                            <td id="res_interes">—</td>
                        </tr>
                        <tr id="row_total_credito" style="display:none;">
                            <th>Total con Interés:</th>
                            <td id="res_total_credito" class="fw-bold text-danger">—</td>
                        </tr>
                        <tr id="row_cuota" style="display:none;">
                            <th>Monto por Cuota:</th>
                            <td id="res_cuota" class="fw-bold">—</td>
                        </tr>
                        <tr id="row_descuento" style="display:none;">
                            <th>Descuento:</th>
                            <td id="res_descuento">—</td>
                        </tr>
                    </table>
                    <hr>
                    <div class="text-center">
                        <div style="font-size:1.6rem; font-family:'Cinzel',serif; color:var(--color-primary);" id="res_final">—</div>
                        <small class="text-muted" id="res_moneda">—</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-gold w-100">
                        <i class="bi bi-check-circle me-1"></i>Confirmar Venta
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const radioContado = document.getElementById('tipo_contado');
    const radioCredito = document.getElementById('tipo_credito');
    const panelContado = document.getElementById('panel_contado');
    const panelCredito = document.getElementById('panel_credito');

    function togglePaneles() {
        const esCredito = radioCredito.checked;
        panelContado.style.display = esCredito ? 'none' : 'block';
        panelCredito.style.display = esCredito ? 'block' : 'none';
        document.getElementById('row_interes').style.display = esCredito ? '' : 'none';
        document.getElementById('row_total_credito').style.display = esCredito ? '' : 'none';
        document.getElementById('row_cuota').style.display = esCredito ? '' : 'none';
        document.getElementById('row_descuento').style.display = esCredito ? 'none' : '';
        calcularResumen();
    }

    function calcularResumen() {
        const precio = parseFloat(document.getElementById('precio_total').value) || 0;
        const moneda = document.getElementById('moneda').value;
        const esCredito = radioCredito.checked;

        document.getElementById('res_precio').textContent = precio.toFixed(2) + ' ' + moneda;

        if (esCredito) {
            const interes = parseFloat(document.getElementById('interes').value) || 0;
            const nCuotas = parseInt(document.getElementById('nro_cuotas').value) || 1;
            const total = precio * (1 + interes / 100);
            const cuota = total / nCuotas;

            document.getElementById('res_interes').textContent = interes + '%';
            document.getElementById('res_total_credito').textContent = total.toFixed(2) + ' ' + moneda;
            document.getElementById('res_cuota').textContent = cuota.toFixed(2) + ' ' + moneda;
            document.getElementById('res_final').textContent = total.toFixed(2);
            document.getElementById('res_moneda').textContent = moneda + ' (con interés)';

            // Auto-calcular fecha_fin
            autoFechaFin();
        } else {
            const descuento = parseFloat(document.querySelector('[name=descuento]')?.value) || 0;
            const total = precio - descuento;
            document.getElementById('res_descuento').textContent = '- ' + descuento.toFixed(2);
            document.getElementById('res_final').textContent = total.toFixed(2);
            document.getElementById('res_moneda').textContent = moneda;
        }
    }

    function autoFechaFin() {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const nCuotas = parseInt(document.getElementById('nro_cuotas').value) || 1;
        const frecuencia = document.getElementById('frecuencia').value;

        if (!fechaInicio) return;

        const dias = frecuencia === 'semanal' ? 7 : frecuencia === 'quincenal' ? 15 : 30;
        const fecha = new Date(fechaInicio);
        fecha.setDate(fecha.getDate() + dias * nCuotas);

        document.getElementById('fecha_fin').value = fecha.toISOString().split('T')[0];
    }

    // Auto-cargar cliente_id desde contrato
    document.getElementById('contrato_id').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        const monto = opt.dataset.monto;
        const moneda = opt.dataset.moneda;
        const cliente = opt.dataset.cliente;

        if (monto) document.getElementById('precio_total').value = parseFloat(monto).toFixed(2);
        if (moneda) document.getElementById('moneda').value = moneda;
        if (cliente) document.getElementById('cliente_id').value = cliente;
        calcularResumen();
    });

    // Listeners
    radioContado.addEventListener('change', togglePaneles);
    radioCredito.addEventListener('change', togglePaneles);
    document.getElementById('precio_total').addEventListener('input', calcularResumen);
    document.getElementById('interes')?.addEventListener('input', calcularResumen);
    document.getElementById('nro_cuotas')?.addEventListener('input', () => {
        calcularResumen();
        autoFechaFin();
    });
    document.getElementById('frecuencia')?.addEventListener('change', () => {
        calcularResumen();
        autoFechaFin();
    });
    document.getElementById('fecha_inicio')?.addEventListener('change', autoFechaFin);
    document.querySelector('[name=descuento]')?.addEventListener('input', calcularResumen);

    // Init
    togglePaneles();
    @if(request('contrato_id'))
    document.getElementById('contrato_id').value = '{{ request("contrato_id") }}';
    document.getElementById('contrato_id').dispatchEvent(new Event('change'));
    @endif
</script>
@endpush