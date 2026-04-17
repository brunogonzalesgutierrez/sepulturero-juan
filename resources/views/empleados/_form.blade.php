<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">CI <span class="text-danger">*</span></label>
        <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror"
            value="{{ old('ci', $empleado->ci ?? '') }}" required>
        @error('ci')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $empleado->nombre ?? '') }}" required>
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Ap. Paterno <span class="text-danger">*</span></label>
        <input type="text" name="paterno" class="form-control @error('paterno') is-invalid @enderror"
            value="{{ old('paterno', $empleado->paterno ?? '') }}" required>
        @error('paterno')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Ap. Materno</label>
        <input type="text" name="materno" class="form-control @error('materno') is-invalid @enderror"
            value="{{ old('materno', $empleado->materno ?? '') }}">
        @error('materno')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Teléfono</label>
        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
            value="{{ old('telefono', $empleado->telefono ?? '') }}">
        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Cargo</label>
        <input type="text" name="cargo" class="form-control @error('cargo') is-invalid @enderror"
            value="{{ old('cargo', $empleado->cargo ?? '') }}">
        @error('cargo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8">
        <label class="form-label fw-semibold">Dirección</label>
        <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
            value="{{ old('direccion', $empleado->direccion ?? '') }}">
        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
            <option value="activo" {{ old('estado', $empleado->estado ?? 'activo') == 'activo'   ? 'selected':'' }}>Activo</option>
            <option value="inactivo" {{ old('estado', $empleado->estado ?? '') == 'inactivo' ? 'selected':'' }}>Inactivo</option>
        </select>
        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>