<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Contrato <span class="text-danger">*</span></label>
        <select name="contrato_id" id="contrato_id"
            class="form-select @error('contrato_id') is-invalid @enderror" required>
            <option value="">Seleccione un contrato activo...</option>
            @foreach($contratos as $c)
            <option value="{{ $c->id }}"
                data-espacio="{{ $c->espacio_id }}"
                {{ old('contrato_id', $inhumacion->contrato_id ?? '') == $c->id ? 'selected':'' }}>
                #{{ $c->id }} — {{ $c->cliente->nombre }} {{ $c->cliente->paterno }}
                (CI: {{ $c->cliente->ci }}) | Espacio #{{ $c->espacio_id }}
            </option>
            @endforeach
        </select>
        @error('contrato_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Espacio <span class="text-danger">*</span></label>
        <select name="espacio_id" id="espacio_id"
            class="form-select @error('espacio_id') is-invalid @enderror" required>
            <option value="">Seleccione primero un contrato...</option>
            @foreach($espacios as $e)
            <option value="{{ $e->id }}"
                {{ old('espacio_id', $inhumacion->espacio_id ?? '') == $e->id ? 'selected':'' }}>
                #{{ $e->id }} — {{ $e->cementerio->nombre }} |
                {{ $e->tipoInhumacion->nombre }} |
                Secc {{ $e->direccion->seccion ?? '?' }} Fila {{ $e->direccion->fila ?? '?' }} Nro {{ $e->direccion->numero ?? '?' }}
            </option>
            @endforeach
        </select>
        @error('espacio_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <hr class="my-1"><small class="text-muted fw-semibold">DATOS DEL FALLECIDO</small>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $inhumacion->nombre ?? '') }}" required>
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Ap. Paterno <span class="text-danger">*</span></label>
        <input type="text" name="paterno" class="form-control @error('paterno') is-invalid @enderror"
            value="{{ old('paterno', $inhumacion->paterno ?? '') }}" required>
        @error('paterno')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Ap. Materno</label>
        <input type="text" name="materno" class="form-control @error('materno') is-invalid @enderror"
            value="{{ old('materno', $inhumacion->materno ?? '') }}">
        @error('materno')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Fecha de Nacimiento</label>
        <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
            value="{{ old('fecha_nacimiento', $inhumacion->fecha_nacimiento?->format('Y-m-d') ?? '') }}">
        @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Fecha de Defunción <span class="text-danger">*</span></label>
        <input type="date" name="fecha_defuncion" class="form-control @error('fecha_defuncion') is-invalid @enderror"
            value="{{ old('fecha_defuncion', $inhumacion->fecha_defuncion?->format('Y-m-d') ?? '') }}" required>
        @error('fecha_defuncion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Fecha de Inhumación <span class="text-danger">*</span></label>
        <input type="date" name="fecha_inhumacion" class="form-control @error('fecha_inhumacion') is-invalid @enderror"
            value="{{ old('fecha_inhumacion', $inhumacion->fecha_inhumacion?->format('Y-m-d') ?? '') }}" required>
        @error('fecha_inhumacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-12">
        <label class="form-label fw-semibold">Causa de Muerte</label>
        <input type="text" name="causa_muerte" class="form-control @error('causa_muerte') is-invalid @enderror"
            value="{{ old('causa_muerte', $inhumacion->causa_muerte ?? '') }}">
        @error('causa_muerte')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('contrato_id').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        const espacioId = opt?.dataset?.espacio;

        const selectEspacio = document.getElementById('espacio_id');

        // Resetear
        selectEspacio.value = '';

        if (espacioId) {
            // Buscar el option con ese valor y seleccionarlo
            const opciones = selectEspacio.options;
            for (let i = 0; i < opciones.length; i++) {
                if (opciones[i].value == espacioId) {
                    selectEspacio.value = espacioId;
                    break;
                }
            }
        }
    });
</script>
@endpush