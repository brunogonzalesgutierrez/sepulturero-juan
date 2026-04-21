<div class="row g-3">
    <div class="col-md-12">
        <label class="form-label fw-semibold">Cuota a Pagar <span class="text-danger">*</span></label>
        <select name="cuota_id" id="cuota_id"
            class="form-select @error('cuota_id') is-invalid @enderror" required>
            <option value="">Seleccione una cuota pendiente...</option>
            @foreach($cuotasPendientes as $cuota)
            @php
            $cliente = $cuota->planPago->pagoCredito->venta->cliente;
            $venta = $cuota->planPago->pagoCredito->venta;
            @endphp
            <option value="{{ $cuota->id }}"
                data-monto="{{ $cuota->monto }}"
                class="{{ $cuota->estado == 'vencida' ? 'text-danger' : '' }}"
                {{ old('cuota_id', $pago->cuota_id ?? '') == $cuota->id ? 'selected':'' }}>
                Cuota #{{ $cuota->nro_cuota }} —
                {{ $cliente->nombre }} {{ $cliente->paterno }} (CI: {{ $cliente->ci }}) |
                Vence: {{ $cuota->fecha_vencimiento->format('d/m/Y') }} |
                Monto: {{ number_format($cuota->monto, 2) }}
                {{ $cuota->estado == 'vencida' ? '[VENCIDA]' : '' }}
            </option>
            @endforeach
        </select>
        @error('cuota_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Empleado que Cobra <span class="text-danger">*</span></label>
        <select name="empleado_id" class="form-select @error('empleado_id') is-invalid @enderror" required>
            <option value="">Seleccione...</option>
            @foreach($empleados as $e)
            <option value="{{ $e->id }}"
                {{ old('empleado_id', $pago->empleado_id ?? auth()->user()->empleado_id) == $e->id ? 'selected':'' }}>
                {{ $e->nombre }} {{ $e->paterno }}
            </option>
            @endforeach
        </select>
        @error('empleado_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Fecha de Pago <span class="text-danger">*</span></label>
        <input type="date" name="fecha_pago"
            class="form-control @error('fecha_pago') is-invalid @enderror"
            value="{{ old('fecha_pago', $pago->fecha_pago?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
            required>
        @error('fecha_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Monto Pagado <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="monto_pagado" id="monto_pagado"
            class="form-control @error('monto_pagado') is-invalid @enderror"
            value="{{ old('monto_pagado', $pago->monto_pagado ?? '') }}"
            required min="0.01">
        @error('monto_pagado')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Interés adicional</label>
        <input type="number" step="0.01" name="monto_interes"
            class="form-control @error('monto_interes') is-invalid @enderror"
            value="{{ old('monto_interes', $pago->monto_interes ?? 0) }}" min="0">
        @error('monto_interes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Método de Pago <span class="text-danger">*</span></label>
        <select name="metodo_pago" class="form-select @error('metodo_pago') is-invalid @enderror" required>
            @foreach(['efectivo','transferencia','tarjeta','qr'] as $mp)
            <option value="{{ $mp }}"
                {{ old('metodo_pago', $pago->metodo_pago ?? 'efectivo') == $mp ? 'selected':'' }}>
                {{ ucfirst($mp) }}
            </option>
            @endforeach
        </select>
        @error('metodo_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Nro. Comprobante</label>
        <input type="text" name="comprobante"
            class="form-control @error('comprobante') is-invalid @enderror"
            value="{{ old('comprobante', $pago->comprobante ?? '') }}"
            placeholder="Ej: REC-001">
        @error('comprobante')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Resumen cuota seleccionada --}}
    <div class="col-md-6">
        <div class="alert alert-info py-2 mb-0" id="resumen_cuota" style="display:none;">
            <small>
                <strong>Cuota seleccionada:</strong>
                Monto: <span id="cuota_monto">—</span> |
                <span id="cuota_estado_msg"></span>
            </small>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('cuota_id').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        const monto = opt.dataset.monto;
        if (monto) {
            document.getElementById('monto_pagado').value = parseFloat(monto).toFixed(2);
            document.getElementById('cuota_monto').textContent = parseFloat(monto).toFixed(2);
            document.getElementById('resumen_cuota').style.display = '';
            document.getElementById('cuota_estado_msg').textContent =
                opt.text.includes('[VENCIDA]') ? '⚠ Cuota VENCIDA' : 'Cuota pendiente';
        }
    });
</script>
@endpush