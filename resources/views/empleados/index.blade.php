@extends('layouts.app')
@section('title', 'Empleados')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-person-badge me-2"></i>Empleados</h1>
    {{-- @can('empleados.crear') --}}
    <a href="{{ route('empleados.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Empleado
    </a>
    {{-- @endcan --}}
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="buscar" class="form-control form-control-sm"
                    placeholder="CI, nombre, apellido o cargo..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="activo" {{ request('estado')=='activo'   ? 'selected':'' }}>Activo</option>
                    <option value="inactivo" {{ request('estado')=='inactivo' ? 'selected':'' }}>Inactivo</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button>
                <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
                        <th>#</th>
                        <th>CI</th>
                        <th>Nombre Completo</th>
                        <th>Cargo</th>
                        <th>Teléfono</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empleados as $e)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $e->id }}</td>
                        <td><strong>{{ $e->ci }}</strong></td>
                        <td>{{ $e->nombre }} {{ $e->paterno }} {{ $e->materno }}</td>
                        <td>{{ $e->cargo ?? '—' }}</td>
                        <td>{{ $e->telefono ?? '—' }}</td>
                        <td>
                            @if($e->usuario)
                            <span class="badge bg-success">{{ $e->usuario->username }}</span>
                            @else
                            <span class="badge bg-secondary">Sin usuario</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $e->estado }}">{{ ucfirst($e->estado) }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('empleados.show', $e) }}" class="btn btn-sm btn-outline-secondary" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            {{-- @can('empleados.editar') --}}
                            <a href="{{ route('empleados.edit', $e) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            {{-- @endcan --}}
                            {{-- @can('empleados.eliminar') --}}
                            <form method="POST" action="{{ route('empleados.destroy', $e) }}" class="d-inline"
                                onsubmit="return confirm('¿Eliminar empleado {{ addslashes($e->nombre) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            {{-- @endcan --}}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>No se encontraron empleados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($empleados->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">Mostrando {{ $empleados->firstItem() }}–{{ $empleados->lastItem() }} de {{ $empleados->total() }}</small>
        {{ $empleados->links() }}
    </div>
    @endif
</div>
@endsection