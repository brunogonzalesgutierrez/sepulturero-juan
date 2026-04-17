<div class="row g-3">
    <div class="col-md-12">
        <label class="form-label fw-semibold">Espacio <span class="text-danger">*</span></label>
        <select name="espacio_id" class="form-select @error('espacio_id') is-invalid @enderror" required>
            <option value="">Seleccione un espacio...</option>
            @foreach($espacios as $e)
            <option value="{{ $e->id }}"
                {{ old('espacio_id', $mantenimiento->espacio_id ?? '') == $e->id ? 'selected':'' }}>
                #{{ $e->id }} — {{ $e->cementerio->nombre }} |
                Secc: {{ $e->direccion->seccion ?? '?' }}
                Fila: {{ $e->direccion->fila ?? '?' }}
                Nro: {{ $e->direccion->numero ?? '?' }}
                [{{ ucfirst($e->estado) }}]
            </option>
            @endforeach
        </select>
        @error('espacio_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Tipo <span class="text-danger">*</span></label>
        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
            @foreach(['limpieza','reparacion','renovacion','otro'] as $t)
            <option value="{{ $t }}" {{ old('tipo', $mantenimiento->tipo ?? '') == $t ? 'selected':'' }}>
                {{ ucfirst($t) }}
            </option>
            @endforeach
        </select>
        @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Precio (BOB) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="precio"
            class="form-control @error('precio') is-invalid @enderror"
            value="{{ old('precio', $mantenimiento->precio ?? '') }}" required>
        @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
            @foreach(['pendiente','en_proceso','completado'] as $est)
            <option value="{{ $est }}" {{ old('estado', $mantenimiento->estado ?? 'pendiente') == $est ? 'selected':'' }}>
                {{ ucfirst(str_replace('_',' ',$est)) }}
            </option>
            @endforeach
        </select>
        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Fecha Inicio</label>
        <input type="date" name="fecha_inicio"
            class="form-control @error('fecha_inicio') is-invalid @enderror"
            value="{{ old('fecha_inicio', $mantenimiento->fecha_inicio?->format('Y-m-d') ?? '') }}">
        @error('fecha_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Fecha Fin</label>
        <input type="date" name="fecha_fin"
            class="form-control @error('fecha_fin') is-invalid @enderror"
            value="{{ old('fecha_fin', $mantenimiento->fecha_fin?->format('Y-m-d') ?? '') }}">
        @error('fecha_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-12">
        <label class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
        <textarea name="descripcion" rows="3"
            class="form-control @error('descripcion') is-invalid @enderror"
            required>{{ old('descripcion', $mantenimiento->descripcion ?? '') }}</textarea>
        @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>