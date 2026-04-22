@extends('layouts.app')
@section('title', 'Inhumaciones')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-flower1 me-2"></i>Inhumaciones</h1>
    @can('inhumaciones.ver')
    <a href="{{ route('inhumaciones.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i>Nueva Inhumación
    </a>
    @endcan
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="buscar" class="form-control form-control-sm"
                    placeholder="Nombre del fallecido, CI cliente..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <select name="cementerio_id" class="form-select form-select-sm">
                    <option value="">Todos los cementerios</option>
                    @foreach($cementerios as $c)
                    <option value="{{ $c->id }}" {{ request('cementerio_id') == $c->id ? 'selected':'' }}>
                        {{ $c->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button>
                <a href="{{ route('inhumaciones.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
                        <th>Fallecido</th>
                        <th>F. Defunción</th>
                        <th>F. Inhumación</th>
                        <th>Espacio</th>
                        <th>Cliente (Titular)</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inhumaciones as $inh)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $inh->id }}</td>
                        <td>
                            <strong>{{ $inh->nombre }} {{ $inh->paterno }}</strong>
                            @if($inh->materno) {{ $inh->materno }} @endif
                        </td>
                        <td>{{ $inh->fecha_defuncion->format('d/m/Y') }}</td>
                        <td>{{ $inh->fecha_inhumacion->format('d/m/Y') }}</td>
                        <td>
                            <small>{{ $inh->espacio->cementerio->nombre }}</small><br>
                            Secc {{ $inh->espacio->direccion->seccion ?? '?' }} /
                            {{ $inh->espacio->direccion->numero ?? '?' }}
                        </td>
                        <td>
                            {{ $inh->contrato->cliente->nombre }}
                            {{ $inh->contrato->cliente->paterno }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('inhumaciones.show', $inh) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('inhumaciones.ver')
                            <a href="{{ route('inhumaciones.edit', $inh) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('inhumaciones.ver')
                            <form method="POST" action="{{ route('inhumaciones.destroy', $inh) }}" class="d-inline"
                                onsubmit="return confirm('¿Eliminar esta inhumación?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>No hay inhumaciones registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($inhumaciones->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">{{ $inhumaciones->firstItem() }}–{{ $inhumaciones->lastItem() }} de {{ $inhumaciones->total() }}</small>
        {{ $inhumaciones->links() }}
    </div>
    @endif
</div>
@endsection