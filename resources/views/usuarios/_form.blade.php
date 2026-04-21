<div class="row g-3">

    {{-- EMPLEADO --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Empleado <span class="text-danger">*</span></label>
        <select name="empleado_id" class="form-select @error('empleado_id') is-invalid @enderror" required>
            <option value="">Seleccione un empleado...</option>
            @foreach($empleados as $emp)
            <option value="{{ $emp->id }}"
                {{ old('empleado_id', isset($usuario) ? $usuario->empleado_id : '') == $emp->id ? 'selected' : '' }}>
                {{ $emp->nombre }} {{ $emp->paterno }} — CI: {{ $emp->ci }}
            </option>
            @endforeach
        </select>
        @error('empleado_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ROL --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Rol <span class="text-danger">*</span></label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="">Seleccione un rol...</option>
            @foreach($roles as $rol)
            <option value="{{ $rol->name }}"
                {{ old('role', isset($usuario) ? $usuario->getRoleNames()->first() : '') == $rol->name ? 'selected' : '' }}>
                {{ $rol->name }}
            </option>
            @endforeach
        </select>
        @error('role')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- USERNAME --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
        <input type="text" name="username"
            class="form-control @error('username') is-invalid @enderror"
            value="{{ old('username', isset($usuario) ? $usuario->username : '') }}" required>
        @error('username')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- EMAIL --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
        <input type="email" name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', isset($usuario) ? $usuario->email : '') }}" required>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- PASSWORD --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Contraseña {{ isset($usuario) ? '(dejar vacío para no cambiar)' : '' }}
            <span class="text-danger">*</span>
        </label>
        <input type="password" name="password"
            class="form-control @error('password') is-invalid @enderror"
            autocomplete="new-password">
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- CONFIRM PASSWORD --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Confirmar Contraseña</label>
        <input type="password" name="password_confirmation"
            class="form-control" autocomplete="new-password">
    </div>

    {{-- ESTADO --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
            <option value="activo"
                {{ old('estado', isset($usuario) ? $usuario->estado : 'activo') == 'activo' ? 'selected' : '' }}>
                Activo
            </option>
            <option value="inactivo"
                {{ old('estado', isset($usuario) ? $usuario->estado : '') == 'inactivo' ? 'selected' : '' }}>
                Inactivo
            </option>
        </select>
        @error('estado')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>