<div class="row g-3">

    {{-- CLIENTE --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Cliente <span class="text-danger">*</span></label>
        <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
            <option value="">Seleccione...</option>
            @foreach($clientes as $c)
            <option value="{{ $c->id }}"
                {{ old('cliente_id', $contrato?->cliente_id) == $c->id ? 'selected' : '' }}>
                {{ $c->nombre }} {{ $c->paterno }} (CI: {{ $c->ci }})
            </option>
            @endforeach
        </select>
        @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- ESPACIO --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Espacio <span class="text-danger">*</span></label>
        <select name="espacio_id" class="form-select @error('espacio_id') is-invalid @enderror" required>
            <option value="">Seleccione...</option>
            @foreach($espacios as $e)
            <option value="{{ $e->id }}"
                {{ old('espacio_id', $contrato?->espacio_id) == $e->id ? 'selected' : '' }}>
                {{ $e->codigo ?? 'Espacio #' . $e->id }} - {{ $e->cementerio->nombre ?? '' }}
            </option>
            @endforeach
        </select>
        @error('espacio_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- FECHA CONTRATO --}}
    <div class="col-md-3">
        <label class="form-label fw-semibold">Fecha Contrato <span class="text-danger">*</span></label>
        <input type="date" name="fecha_contrato"
            class="form-control @error('fecha_contrato') is-invalid @enderror"
            value="{{ old('fecha_contrato', $contrato?->fecha_contrato?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
            required>
        @error('fecha_contrato')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- MONTO BASE --}}
    <div class="col-md-3">
        <label class="form-label fw-semibold">Monto Base <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="monto_base"
            class="form-control @error('monto_base') is-invalid @enderror"
            value="{{ old('monto_base', $contrato?->monto_base ?? '') }}" required>
        @error('monto_base')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- MONEDA --}}
    <div class="col-md-3">
        <label class="form-label fw-semibold">Moneda <span class="text-danger">*</span></label>
        <select name="moneda" class="form-select @error('moneda') is-invalid @enderror" required>
            <option value="BOB" {{ old('moneda', $contrato?->moneda ?? 'BOB') == 'BOB' ? 'selected' : '' }}>BOB</option>
            <option value="USD" {{ old('moneda', $contrato?->moneda ?? '') == 'USD' ? 'selected' : '' }}>USD</option>
        </select>
        @error('moneda')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- ESTADO --}}
    <div class="col-md-3">
        <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
            <option value="activo" {{ old('estado', $contrato?->estado ?? 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="finalizado" {{ old('estado', $contrato?->estado ?? '') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
            <option value="cancelado" {{ old('estado', $contrato?->estado ?? '') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
        </select>
        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

</div>

@push('scripts')
<script>
    // Auto-rellenar monto base al seleccionar espacio
    document.getElementById('espacio_select')?.addEventListener('change', function() {
        const precio = this.options[this.selectedIndex]?.dataset?.precio;
        if (precio) document.getElementById('monto_base').value = parseFloat(precio).toFixed(2);
    });
</script>
@endpush