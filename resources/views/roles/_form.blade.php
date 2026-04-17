<div class="mb-3">
    <label class="form-label fw-semibold">Nombre del Rol <span class="text-danger">*</span></label>
    <input type="text" name="nombre"
        class="form-control @error('nombre') is-invalid @enderror"
        value="{{ old('nombre', $role->name ?? '') }}"
        {{ isset($role) && in_array($role->name, ['Administrador','Cajero','Operador','Supervisor']) ? 'readonly' : '' }}
        required>
    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    @if(isset($role) && in_array($role->name, ['Administrador','Cajero','Operador','Supervisor']))
    <small class="text-muted">Los roles del sistema no pueden renombrarse.</small>
    @endif
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Permisos</label>
    <div class="row g-3">
        @foreach($permisos as $modulo => $listaPermisos)
        <div class="col-md-4">
            <div class="card">
                <div class="card-header py-1" style="font-size:0.8rem;">
                    <div class="form-check mb-0">
                        <input type="checkbox" class="form-check-input modulo-check"
                            id="mod_{{ $modulo }}" data-modulo="{{ $modulo }}">
                        <label class="form-check-label fw-bold text-uppercase" for="mod_{{ $modulo }}"
                            style="letter-spacing:1px; font-size:0.75rem; cursor:pointer;">
                            {{ $modulo }}
                        </label>
                    </div>
                </div>
                <div class="card-body py-2">
                    @foreach($listaPermisos as $permiso)
                    <div class="form-check">
                        <input type="checkbox"
                            class="form-check-input permiso-check permiso-{{ $modulo }}"
                            name="permisos[]"
                            value="{{ $permiso->name }}"
                            id="perm_{{ $permiso->id }}"
                            {{ isset($permisosActivos) && in_array($permiso->name, $permisosActivos) ? 'checked' : '' }}>
                        <label class="form-check-label" for="perm_{{ $permiso->id }}" style="font-size:0.85rem;">
                            {{ explode('.', $permiso->name)[1] ?? $permiso->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    // Toggle módulo completo
    document.querySelectorAll('.modulo-check').forEach(chk => {
        chk.addEventListener('change', function() {
            const mod = this.dataset.modulo;
            document.querySelectorAll('.permiso-' + mod).forEach(p => p.checked = this.checked);
        });
    });

    // Marcar módulo si todos los permisos están activos
    document.querySelectorAll('.modulo-check').forEach(chk => {
        const mod = chk.dataset.modulo;
        const perms = document.querySelectorAll('.permiso-' + mod);
        const todos = Array.from(perms).every(p => p.checked);
        chk.checked = todos;
    });
</script>
@endpush