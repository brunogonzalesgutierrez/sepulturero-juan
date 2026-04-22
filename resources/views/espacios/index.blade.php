@extends('layouts.app')
@section('title', 'Espacios')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-grid-3x3-gap me-2"></i>Espacios Funerarios</h1>
    @can('espacios.ver')
    <a href="{{ route('espacios.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Espacio
    </a>
    @endcan
</div>

{{-- Estadísticas rápidas --}}
<div class="row g-2 mb-3">
    @foreach(['disponible' => ['success','check-circle'],'ocupado' => ['danger','x-circle'],'mantenimiento' => ['warning','tools'],'reservado' => ['info','bookmark']] as $est => [$color, $icon])
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color: var(--bs-{{ $color }});">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number" style="font-size:1.4rem;">
                        {{ $contadores[$est] }}
                    </div>
                    <div class="stat-label">{{ ucfirst($est) }}</div>
                </div>
                <i class="bi bi-{{ $icon }} stat-icon"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filtros --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <select name="cementerio_id" class="form-select form-select-sm">
                    <option value="">Todos los cementerios</option>
                    @foreach($cementerios as $c)
                    <option value="{{ $c->id }}" {{ request('cementerio_id') == $c->id ? 'selected':'' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="tipo_inhumacion_id" class="form-select form-select-sm">
                    <option value="">Todos los tipos</option>
                    @foreach($tipos as $t)
                    <option value="{{ $t->id }}" {{ request('tipo_inhumacion_id') == $t->id ? 'selected':'' }}>{{ $t->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    @foreach(['disponible','ocupado','mantenimiento','reservado'] as $est)
                    <option value="{{ $est }}" {{ request('estado') == $est ? 'selected':'' }}>{{ ucfirst($est) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button>
                <a href="{{ route('espacios.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
                        <th>Cementerio</th>
                        <th>Tipo</th>
                        <th>Sección</th>
                        <th>Fila/Nro</th>
                        <th>Dimensión</th>
                        <th>Precio m²</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($espacios as $e)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $e->id }}</td>
                        <td>{{ $e->cementerio->nombre }}</td>
                        <td>{{ $e->tipoInhumacion->nombre }}</td>
                        <td>{{ $e->direccion->seccion ?? '—' }}</td>
                        <td>{{ $e->direccion->fila ?? '—' }} / {{ $e->direccion->numero ?? '—' }}</td>
                        <td>
                            @if($e->dimension)
                            {{ $e->dimension->ancho }}m × {{ $e->dimension->largo }}m
                            @else —
                            @endif
                        </td>
                        <td>{{ number_format($e->precio_m2, 2) }}</td>
                        <td><span class="badge badge-{{ $e->estado }}">{{ ucfirst($e->estado) }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('espacios.show', $e) }}" class="btn btn-sm btn-outline-secondary" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('espacios.ver')
                            <a href="{{ route('espacios.edit', $e) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('espacios.ver')
                            <form method="POST" action="{{ route('espacios.destroy', $e) }}" class="d-inline"
                                onsubmit="return confirm('¿Eliminar espacio #{{ $e->id }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>No hay espacios registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($espacios->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">{{ $espacios->firstItem() }}–{{ $espacios->lastItem() }} de {{ $espacios->total() }}</small>
        {{ $espacios->links() }}
    </div>
    @endif
</div>
@endsection