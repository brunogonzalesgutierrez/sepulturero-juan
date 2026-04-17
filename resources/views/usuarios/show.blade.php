@extends('layouts.app')
@section('title', 'Detalle Usuario')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-person-circle me-2"></i>{{ $usuario->username }}</h1>
    <div class="d-flex gap-2">
        @can('usuarios.editar')
        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-gold">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endcan
        <a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="card" style="max-width:560px;">
    <div class="card-header py-2"><i class="bi bi-info-circle me-1"></i>Información de cuenta</div>
    <div class="card-body">
        <table class="table table-sm table-borderless mb-0">
            <tr>
                <th style="width:40%">Username</th>
                <td>{{ $usuario->username }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $usuario->email }}</td>
            </tr>
            <tr>
                <th>Empleado</th>
                <td>{{ $usuario->empleado?->nombre }} {{ $usuario->empleado?->paterno }}</td>
            </tr>
            <tr>
                <th>Rol</th>
                <td><span class="badge bg-dark">{{ $usuario->getRoleNames()->first() ?? '—' }}</span></td>
            </tr>
            <tr>
                <th>Estado</th>
                <td><span class="badge badge-{{ $usuario->estado }}">{{ ucfirst($usuario->estado) }}</span></td>
            </tr>
            <tr>
                <th>Intentos fallidos</th>
                <td>{{ $usuario->intentos_fallidos }}</td>
            </tr>
            <tr>
                <th>Bloqueado hasta</th>
                <td>{{ $usuario->bloqueado_hasta ? $usuario->bloqueado_hasta->format('d/m/Y H:i') : '—' }}</td>
            </tr>
            <tr>
                <th>Registrado</th>
                <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection