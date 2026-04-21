@extends('layouts.app')
@section('title', 'Ventas')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-cart-check me-2"></i>Ventas</h1>
    @can('ventas.crear')
    <a href="{{ route('ventas.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i>Nueva Venta
    </a>
    @endcan
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="buscar" class="form-control form-control-sm"
                    placeholder="CI, nombre o apellido del cliente..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2">
                <select name="tipo_venta" class="form-select form-select-sm">
                    <option value="">Tipo</option>
                    <option value="contado" {{ request('tipo_venta')=='contado' ? 'selected':'' }}>Contado</option>
                    <option value="credito" {{ request('tipo_venta')=='credito' ? 'selected':'' }}>Crédito</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="moneda" class="form-select form-select-sm">
                    <option value="">Moneda</option>
                    <option value="BOB" {{ request('moneda')=='BOB' ? 'selected':'' }}>BOB</option>
                    <option value="USD" {{ request('moneda')=='USD' ? 'selected':'' }}>USD</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button>
                <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
                        <th>Cliente</th>
                        <th>Espacio</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Moneda</th>
                        <th>Tipo</th>
                        <th>Vendedor</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $v)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $v->id }}</td>
                        <td>
                            <strong>{{ $v->cliente->nombre }} {{ $v->cliente->paterno }}</strong>
                        </td>
                        <td>
                            <small>{{ $v->contrato->espacio->cementerio->nombre }}</small>
                        </td>
                        <td>{{ $v->fecha_venta->format('d/m/Y') }}</td>
                        <td><strong>{{ number_format($v->precio_total, 2) }}</strong></td>
                        <td><span class="badge bg-secondary">{{ $v->moneda }}</span></td>
                        <td>
                            <span class="badge {{ $v->tipo_venta == 'contado' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ ucfirst($v->tipo_venta) }}
                            </span>
                        </td>
                        <td>{{ $v->empleado->nombre }} {{ $v->empleado->paterno }}</td>
                        <td class="text-center">
                            <a href="{{ route('ventas.show', $v) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>No hay ventas registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($ventas->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">{{ $ventas->firstItem() }}–{{ $ventas->lastItem() }} de {{ $ventas->total() }}</small>
        {{ $ventas->links() }}
    </div>
    @endif
</div>
@endsection