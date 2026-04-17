@extends('layouts.app')
@section('title', 'Usuarios')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-people me-2"></i>Usuarios del Sistema</h1>
    @can('usuarios.crear')
    <a href="{{ route('usuarios.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Usuario
    </a>
    @endcan
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="buscar" class="form-control form-control-sm"
                    placeholder="Username, email o nombre de empleado..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2">
                <select name="rol" class="form-select form-select-sm">
                    <option value="">Todos los roles</option>
                    @foreach($roles as $rol)
                    <option value="{{ $rol->name }}" {{ request('rol') == $rol->name ? 'selected':'' }}>
                        {{ $rol->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="activo" {{ request('estado')=='activo'   ? 'selected':'' }}>Activo</option>
                    <option value="inactivo" {{ request('estado')=='inactivo' ? 'selected':'' }}>Inactivo</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Empleado</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Intentos</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $u)
                    <tr>
                        <td><strong>{{ $u->username }}</strong></td>
                        <td>{{ $u->empleado?->nombre }} {{ $u->empleado?->paterno }}</td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <span class="badge bg-dark">{{ $u->getRoleNames()->first() ?? '—' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $u->estado }}">{{ ucfirst($u->estado) }}</span>
                        </td>
                        <td>
                            @if($u->bloqueado_hasta && $u->bloqueado_hasta->isFuture())
                            <span class="badge bg-danger">Bloqueado</span>
                            @else
                            {{ $u->intentos_fallidos }}
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('usuarios.show', $u) }}" class="btn btn-sm btn-outline-secondary" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('usuarios.editar')
                            <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('usuarios.eliminar')
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('usuarios.destroy', $u) }}" class="d-inline"
                                onsubmit="return confirm('¿Eliminar usuario {{ addslashes($u->username) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>No se encontraron usuarios.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($usuarios->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">Mostrando {{ $usuarios->firstItem() }}–{{ $usuarios->lastItem() }} de {{ $usuarios->total() }}</small>
        {{ $usuarios->links() }}
    </div>
    @endif
</div>
@endsection