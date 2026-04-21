<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $tipoInhumacion->nombre ?? '') }}"
            placeholder="Ej: Nicho, Lote, Mausoleo, Columbario" required>
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Precio <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="precio" class="form-control @error('precio') is-invalid @enderror"
            value="{{ old('precio', $tipoInhumacion->precio ?? '') }}" required>
        @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Precio Base <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="precio_base" class="form-control @error('precio_base') is-invalid @enderror"
            value="{{ old('precio_base', $tipoInhumacion->precio_base ?? '') }}" required>
        @error('precio_base')<div class="invalid-feedback">{{ $message }}</div>@enderror
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