@extends('layouts.app')
@section('title', 'Clientes')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-person-lines-fill me-2"></i>Clientes</h1>
    @can('clientes.ver')
    <a href="{{ route('clientes.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Cliente
    </a>
    @endcan
</div>

{{-- Filtros --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('clientes.index') }}" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="buscar" class="form-control form-control-sm"
                    placeholder="Buscar por CI, nombre, apellido o teléfono..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="activo" {{ request('estado')=='activo'   ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ request('estado')=='inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
                <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabla --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>CI</th>
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $cliente->id }}</td>
                        <td><strong>{{ $cliente->ci }}</strong></td>
                        <td>{{ $cliente->nombre }} {{ $cliente->paterno }} {{ $cliente->materno }}</td>
                        <td>{{ $cliente->telefono ?? '—' }}</td>
                        <td>{{ $cliente->correo ?? '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $cliente->estado }}">
                                {{ ucfirst($cliente->estado) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('clientes.show', $cliente) }}"
                                class="btn btn-sm btn-outline-secondary" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('clientes.ver')
                            <a href="{{ route('clientes.edit', $cliente) }}"
                                class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('clientes.ver')
                            <form method="POST" action="{{ route('clientes.destroy', $cliente) }}"
                                class="d-inline"
                                onsubmit="return confirm('¿Eliminar cliente {{ addslashes($cliente->nombre) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            No se encontraron clientes.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($clientes->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">
            Mostrando {{ $clientes->firstItem() }}–{{ $clientes->lastItem() }}
            de {{ $clientes->total() }} clientes
        </small>
        {{ $clientes->links() }}
    </div>
    @endif
</div>
@endsection