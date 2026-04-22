@extends('layouts.app')
@section('title', 'Cementerios')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-geo-alt me-2"></i>Cementerios</h1>
    {{-- @can('cementerios.crear') --}}
    <div class="d-flex gap-2">
        <a href="{{ route('tipo_inhumaciones.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-tags me-1"></i>Tipos de Espacio
        </a>
        <a href="{{ route('cementerios.create') }}" class="btn btn-gold">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Cementerio
        </a>
    </div>
    {{-- @endcan --}}
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-7">
                <input type="text" name="buscar" class="form-control form-control-sm"
                    placeholder="Nombre o localización..."
                    value="{{ request('buscar') }}">
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
                <a href="{{ route('cementerios.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Localización</th>
                        <th>Espacios Totales</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cementerios as $c)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $c->id }}</td>
                        <td><strong>{{ $c->nombre }}</strong></td>
                        <td>{{ $c->tipo_cementerio }}</td>
                        <td>{{ $c->localizacion }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $c->espacio_disponible }} espacios</span>
                        </td>
                        <td><span class="badge badge-{{ $c->estado }}">{{ ucfirst($c->estado) }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('cementerios.show', $c) }}" class="btn btn-sm btn-outline-secondary" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            {{-- @can('cementerios.editar') --}}
                            <a href="{{ route('cementerios.edit', $c) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            {{-- @endcan --}}
                            {{-- @can('cementerios.eliminar') --}}
                            <form method="POST" action="{{ route('cementerios.destroy', $c) }}" class="d-inline"
                                onsubmit="return confirm('¿Eliminar {{ addslashes($c->nombre) }}?')">
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
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>No hay cementerios registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($cementerios->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">{{ $cementerios->firstItem() }}–{{ $cementerios->lastItem() }} de {{ $cementerios->total() }}</small>
        {{ $cementerios->links() }}
    </div>
    @endif
</div>
@endsection