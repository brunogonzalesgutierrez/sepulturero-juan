@extends('layouts.app')
@section('title', 'Mantenimientos')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-tools me-2"></i>Mantenimientos</h1>
    @can('mantenimientos.crear')
    <a href="{{ route('mantenimientos.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Mantenimiento
    </a>
    @endcan
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="buscar" class="form-control form-control-sm"
                    placeholder="Descripción o cementerio..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2">
                <select name="tipo" class="form-select form-select-sm">
                    <option value="">Todos los tipos</option>
                    @foreach(['limpieza','reparacion','renovacion','otro'] as $t)
                    <option value="{{ $t }}" {{ request('tipo') == $t ? 'selected':'' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    @foreach(['pendiente','en_proceso','completado'] as $est)
                    <option value="{{ $est }}" {{ request('estado') == $est ? 'selected':'' }}>
                        {{ ucfirst(str_replace('_',' ',$est)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button>
                <a href="{{ route('mantenimientos.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
                        <th>Espacio</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>F. Inicio</th>
                        <th>F. Fin</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mantenimientos as $m)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $m->id }}</td>
                        <td>
                            <small>{{ $m->espacio->cementerio->nombre }}</small><br>
                            <strong>Secc {{ $m->espacio->direccion->seccion ?? '?' }} / {{ $m->espacio->direccion->numero ?? '?' }}</strong>
                        </td>
                        <td><span class="badge bg-secondary">{{ ucfirst($m->tipo) }}</span></td>
                        <td>{{ Str::limit($m->descripcion, 50) }}</td>
                        <td>{{ number_format($m->precio, 2) }}</td>
                        <td>{{ $m->fecha_inicio?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ $m->fecha_fin?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $m->estado }}">
                                {{ ucfirst(str_replace('_',' ',$m->estado)) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('mantenimientos.show', $m) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('mantenimientos.editar')
                            <a href="{{ route('mantenimientos.edit', $m) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('mantenimientos.eliminar')
                            <form method="POST" action="{{ route('mantenimientos.destroy', $m) }}" class="d-inline"
                                onsubmit="return confirm('¿Eliminar este mantenimiento?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>No hay mantenimientos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($mantenimientos->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">{{ $mantenimientos->firstItem() }}–{{ $mantenimientos->lastItem() }} de {{ $mantenimientos->total() }}</small>
        {{ $mantenimientos->links() }}
    </div>
    @endif
</div>
@endsection