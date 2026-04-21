@extends('layouts.app')
@section('title', 'Detalle Empleado')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-person-badge me-2"></i>{{ $empleado->nombre }} {{ $empleado->paterno }}</h1>
    <div class="d-flex gap-2">
        @can('empleados.editar')
        <a href="{{ route('empleados.edit', $empleado) }}" class="btn btn-sm btn-gold">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endcan
        <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-info-circle me-1"></i>Datos Personales</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:40%">CI</th>
                        <td>{{ $empleado->ci }}</td>
                    </tr>
                    <tr>
                        <th>Nombre</th>
                        <td>{{ $empleado->nombre }} {{ $empleado->paterno }} {{ $empleado->materno }}</td>
                    </tr>
                    <tr>
                        <th>Cargo</th>
                        <td>{{ $empleado->cargo ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td>{{ $empleado->telefono ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Dirección</th>
                        <td>{{ $empleado->direccion ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td><span class="badge badge-{{ $empleado->estado }}">{{ ucfirst($empleado->estado) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-person-circle me-1"></i>Cuenta de Usuario</div>
            <div class="card-body">
                @if($empleado->usuario)
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:40%">Username</th>
                        <td>{{ $empleado->usuario->username }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $empleado->usuario->email }}</td>
                    </tr>
                    <tr>
                        <th>Rol</th>
                        <td>
                            <span class="badge bg-dark">{{ $empleado->usuario->getRoleNames()->first() }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td>
                            <span class="badge badge-{{ $empleado->usuario->estado }}">{{ ucfirst($empleado->usuario->estado) }}</span>
                        </td>
                    </tr>
                </table>
                @can('usuarios.editar')
                <a href="{{ route('usuarios.edit', $empleado->usuario) }}" class="btn btn-sm btn-outline-primary mt-2">
                    <i class="bi bi-pencil me-1"></i>Editar usuario
                </a>
                @endcan
                @else
                <p class="text-muted mb-2">Este empleado no tiene usuario asignado.</p>
                @can('usuarios.crear')
                <a href="{{ route('usuarios.create') }}" class="btn btn-sm btn-gold">
                    <i class="bi bi-plus-lg me-1"></i>Crear usuario
                </a>
                @endcan
                @endif
            </div>
        </div>
    </div>
</div>
@endsection