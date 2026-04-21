<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $cementerio->nombre ?? '') }}" required>
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Tipo <span class="text-danger">*</span></label>
        <input type="text" name="tipo_cementerio" class="form-control @error('tipo_cementerio') is-invalid @enderror"
            value="{{ old('tipo_cementerio', $cementerio->tipo_cementerio ?? '') }}"
            placeholder="Ej: Municipal, Privado..." required>
        @error('tipo_cementerio')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8">
        <label class="form-label fw-semibold">Localización <span class="text-danger">*</span></label>
        <input type="text" name="localizacion" class="form-control @error('localizacion') is-invalid @enderror"
            value="{{ old('localizacion', $cementerio->localizacion ?? '') }}" required>
        @error('localizacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2">
        <label class="form-label fw-semibold">Espacios Totales</label>
        <input type="number" name="espacio_total" class="form-control @error('espacio_disponible') is-invalid @enderror"
            value="{{ old('espacio_total', $cementerio->espacio_total ?? 0) }}" min="0">
        @error('espacio_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2">
        <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
            <option value="activo" {{ old('estado', $cementerio->estado ?? 'activo') == 'activo'   ? 'selected':'' }}>Activo</option>
            <option value="inactivo" {{ old('estado', $cementerio->estado ?? '') == 'inactivo' ? 'selected':'' }}>Inactivo</option>
        </select>
        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>