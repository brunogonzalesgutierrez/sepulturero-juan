@extends('layouts.app')
@section('title', 'Roles y Permisos')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-shield-lock me-2"></i>Roles y Permisos</h1>
    {{-- @can('roles.crear') --}}
    <a href="{{ route('roles.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Rol
    </a>
    {{-- @endcan --}}
</div>

<div class="row g-3">
    @foreach($roles as $rol)
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-shield me-1"></i>
                    <strong>{{ $rol->name }}</strong>
                    <span class="badge bg-secondary ms-1">{{ $rol->permissions->count() }} permisos</span>
                </span>
                <div class="d-flex gap-1">
                    {{-- @can('roles.editar') --}}
                    <a href="{{ route('roles.edit', $rol) }}" class="btn btn-sm btn-outline-primary py-0 px-2">
                        <i class="bi bi-pencil"></i>
                    </a>
                    {{-- @endcan --}}
                    {{-- @can('roles.eliminar') --}}
                    @if(!in_array($rol->name, ['Administrador','Cajero','Operador','Supervisor']))
                    <form method="POST" action="{{ route('roles.destroy', $rol) }}" class="d-inline"
                        onsubmit="return confirm('¿Eliminar rol {{ $rol->name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
                    </form>
                    @endif
                    {{-- @endcan --}}
                </div>
            </div>
            <div class="card-body">
                @php
                $permisosPorModulo = $rol->permissions->groupBy(fn($p) => explode('.', $p->name)[0]);
                @endphp
                @foreach($permisosPorModulo as $modulo => $perms)
                <div class="mb-2">
                    <small class="text-muted fw-bold text-uppercase" style="letter-spacing:1px; font-size:0.7rem;">
                        {{ $modulo }}
                    </small><br>
                    @foreach($perms as $p)
                    <span class="badge bg-dark me-1 mb-1" style="font-size:0.7rem;">
                        {{ explode('.', $p->name)[1] ?? $p->name }}
                    </span>
                    @endforeach
                </div>
                @endforeach
                @if($rol->permissions->isEmpty())
                <small class="text-muted">Sin permisos asignados</small>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection