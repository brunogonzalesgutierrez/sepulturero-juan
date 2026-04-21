@extends('layouts.app')
@section('title', 'Mi Perfil')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-person-circle me-2"></i>Mi Perfil</h1>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header py-2"><i class="bi bi-person me-1"></i>Información de Cuenta</div>
            <div class="card-body">
                <form method="POST" action="{{ route('perfil.update') }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username"
                            class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $user->username) }}" required>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rol</label>
                        <input type="text" class="form-control" value="{{ $user->getRoleNames()->first() }}" readonly>
                    </div>
                    <button type="submit" class="btn btn-gold w-100">
                        <i class="bi bi-save me-1"></i>Actualizar Datos
                    </button>
                </form>
            </div>
        </div>

        @if($user->empleado)
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-person-badge me-1"></i>Datos del Empleado</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:40%">Nombre</th>
                        <td>{{ $user->empleado->nombre }} {{ $user->empleado->paterno }}</td>
                    </tr>
                    <tr>
                        <th>CI</th>
                        <td>{{ $user->empleado->ci }}</td>
                    </tr>
                    <tr>
                        <th>Cargo</th>
                        <td>{{ $user->empleado->cargo ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td>{{ $user->empleado->telefono ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td><span class="badge badge-{{ $user->empleado->estado }}">
                                {{ ucfirst($user->empleado->estado) }}
                            </span></td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header py-2"><i class="bi bi-lock me-1"></i>Cambiar Contraseña</div>
            <div class="card-body">
                <form method="POST" action="{{ route('perfil.password') }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contraseña Actual <span class="text-danger">*</span></label>
                        <input type="password" name="password_actual"
                            class="form-control @error('password_actual') is-invalid @enderror" required>
                        @error('password_actual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nueva Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            autocomplete="new-password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Mínimo 8 caracteres, letras y números.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirmar Nueva Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation"
                            class="form-control" autocomplete="new-password" required>
                    </div>
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-key me-1"></i>Cambiar Contraseña
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header py-2"><i class="bi bi-shield-check me-1"></i>Seguridad</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:50%">Estado cuenta</th>
                        <td><span class="badge badge-{{ $user->estado }}">{{ ucfirst($user->estado) }}</span></td>
                    </tr>
                    <tr>
                        <th>Intentos fallidos</th>
                        <td>{{ $user->intentos_fallidos }}</td>
                    </tr>
                    <tr>
                        <th>Bloqueado hasta</th>
                        <td>{{ $user->bloqueado_hasta ? $user->bloqueado_hasta->format('d/m/Y H:i') : 'No bloqueado' }}</td>
                    </tr>
                    <tr>
                        <th>Último acceso</th>
                        <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection