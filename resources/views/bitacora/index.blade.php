@extends('layouts.app')
@section('title', 'Bitácora')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-journal-text me-2"></i>Bitácora del Sistema</h1>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="buscar" class="form-control form-control-sm"
                    placeholder="Descripción, tabla o empleado..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <select name="tabla" class="form-select form-select-sm">
                    <option value="">Todas las tablas</option>
                    @foreach($tablas as $tabla)
                    <option value="{{ $tabla }}" {{ request('tabla') == $tabla ? 'selected':'' }}>{{ $tabla }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button>
                <a href="{{ route('bitacora.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.85rem;">
                <thead>
                    <tr>
                        <th>Fecha / Hora</th>
                        <th>Empleado</th>
                        <th>Tabla</th>
                        <th>Registro</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bitacoras as $b)
                    <tr>
                        <td class="text-muted">{{ $b->fecha_hora->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $b->empleado->nombre }} {{ $b->empleado->paterno }}</td>
                        <td><span class="badge bg-secondary">{{ $b->tabla_afectada }}</span></td>
                        <td>#{{ $b->nro_registro }}</td>
                        <td>{{ $b->transaccion }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>No hay registros en bitácora.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bitacoras->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">{{ $bitacoras->firstItem() }}–{{ $bitacoras->lastItem() }} de {{ $bitacoras->total() }}</small>
        {{ $bitacoras->links() }}
    </div>
    @endif
</div>
@endsection