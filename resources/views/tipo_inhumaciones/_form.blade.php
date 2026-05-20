<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
        <select name="nombre" id="nombre-select"
            class="form-select @error('nombre') is-invalid @enderror"
            onchange="toggleNombreCustom(this.value)" required>
            <option value="">Seleccione un tipo...</option>
            @php
                $tipos = ['Nicho', 'Mausoleo', 'Lote Familiar', 'Espacio Individual', 'Columbario', 'Cripta', 'Osario', 'Otro'];
                $valorActual = old('nombre', $tipoInhumacion->nombre ?? '');
                $esPersonalizado = $valorActual && !in_array($valorActual, $tipos);
            @endphp
            @foreach($tipos as $t)
            <option value="{{ $t }}" {{ $valorActual == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
            <option value="__otro__" {{ $esPersonalizado ? 'selected' : '' }}>Otro (escribir manualmente)</option>
        </select>
        <input type="text" id="nombre-custom"
            class="form-control mt-2 @error('nombre') is-invalid @enderror"
            placeholder="Escribe el nombre del tipo..."
            value="{{ $esPersonalizado ? $valorActual : '' }}"
            style="display: {{ $esPersonalizado ? 'block' : 'none' }};">
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Precio <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="precio" class="form-control @error('precio') is-invalid @enderror"
            value="{{ old('precio', $tipoInhumacion->precio ?? '') }}" required>
        @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        {{-- Por esto: --}}
        <label class="form-label fw-semibold">Precio por m² <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="precio_m2" class="form-control @error('precio_m2') is-invalid @enderror"
            value="{{ old('precio_m2', $tipoInhumacion->precio_m2 ?? '') }}" required>
        @error('precio_m2')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Capacidad Máxima <span class="text-danger">*</span></label>
        <input type="number" name="capacidad_max" class="form-control @error('capacidad_max') is-invalid @enderror"
            value="{{ old('capacidad_max', $tipoInhumacion->capacidad_max ?? '') }}" min="1" required>
        @error('capacidad_max')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Área Base (m²) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="area_base" class="form-control @error('area_base') is-invalid @enderror"
            value="{{ old('area_base', $tipoInhumacion->area_base ?? '') }}" required>
        @error('area_base')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
            <option value="activo" {{ old('estado', $tipoInhumacion->estado ?? 'activo') == 'activo'   ? 'selected':'' }}>Activo</option>
            <option value="inactivo" {{ old('estado', $tipoInhumacion->estado ?? '') == 'inactivo' ? 'selected':'' }}>Inactivo</option>
        </select>
        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>


<script>
    function toggleNombreCustom(val) {
        const custom = document.getElementById('nombre-custom');
        const select = document.getElementById('nombre-select');
        if (val === '__otro__') {
            custom.style.display = 'block';
            custom.required = true;
            custom.name = 'nombre';
            select.name = '_nombre_select'; // quitar del submit
        } else {
            custom.style.display = 'none';
            custom.required = false;
            custom.name = '_nombre_custom';
            select.name = 'nombre'; // volver al submit
        }
    }
    // Ejecutar al cargar por si hay un valor previo
    toggleNombreCustom(document.getElementById('nombre-select').value);
</script>