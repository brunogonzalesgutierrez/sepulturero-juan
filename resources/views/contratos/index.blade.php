@extends('layouts.app')
@section('title', 'Contratos')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-file-earmark-text me-2"></i>Contratos</h1>
    @can('contratos.crear')
    <a href="{{ route('contratos.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Contrato
    </a>
    @endcan
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="buscar" class="form-control form-control-sm"
                    placeholder="CI, nombre o apellido del cliente..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    @foreach(['activo','pagado','vencido','cancelado'] as $est)
                    <option value="{{ $est }}" {{ request('estado') == $est ? 'selected':'' }}>{{ ucfirst($est) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="moneda" class="form-select form-select-sm">
                    <option value="">Moneda</option>
                    <option value="BOB" {{ request('moneda')=='BOB' ? 'selected':'' }}>BOB</option>
                    <option value="USD" {{ request('moneda')=='USD' ? 'selected':'' }}>USD</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button>
                <a href="{{ route('contratos.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
                        <th>Monto Base</th>
                        <th>Saldo</th>
                        <th>Moneda</th>
                        <th>Estado</th>
                        <th>Venta</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contratos as $c)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $c->id }}</td>
                        <td>
                            <strong>{{ $c->cliente->nombre }} {{ $c->cliente->paterno }}</strong><br>
                            <small class="text-muted">CI: {{ $c->cliente->ci }}</small>
                        </td>
                        <td>
                            <small>{{ $c->espacio->cementerio->nombre }}</small><br>
                            Secc {{ $c->espacio->direccion->seccion ?? '?' }}
                            / {{ $c->espacio->direccion->numero ?? '?' }}
                        </td>
                        <td>{{ $c->fecha_contrato->format('d/m/Y') }}</td>
                        <td>{{ number_format($c->monto_base, 2) }}</td>
                        <td>
                            @if($c->saldo_pendiente > 0)
                            <span class="text-danger fw-bold">{{ number_format($c->saldo_pendiente, 2) }}</span>
                            @else
                            <span class="text-success">0.00</span>
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ $c->moneda }}</span></td>
                        <td><span class="badge badge-{{ $c->estado }}">{{ ucfirst($c->estado) }}</span></td>
                        <td>
                            @if($c->venta)
                            <span class="badge bg-success">
                                <i class="bi bi-check-lg"></i> {{ ucfirst($c->venta->tipo_venta) }}
                            </span>
                            @else
                            @can('ventas.crear')
                            <a href="{{ route('ventas.create') }}?contrato_id={{ $c->id }}"
                                class="btn btn-sm btn-gold py-0 px-2">
                                <i class="bi bi-cart-plus"></i> Vender
                            </a>
                            @endcan
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('contratos.show', $c) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('contratos.editar')
                            <a href="{{ route('contratos.edit', $c) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('contratos.editar')
                            @if(!$c->venta)
                            <form method="POST" action="{{ route('contratos.destroy', $c) }}" class="d-inline"
                                onsubmit="return confirm('¿Eliminar contrato #{{ $c->id }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>No hay contratos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($contratos->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">{{ $contratos->firstItem() }}–{{ $contratos->lastItem() }} de {{ $contratos->total() }}</small>
        {{ $contratos->links() }}
    </div>
    @endif
</div>
@endsection