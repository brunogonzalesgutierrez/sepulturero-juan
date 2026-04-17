@extends('layouts.app')
@section('title', 'Detalle Cliente')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-person-circle me-2"></i>{{ $cliente->nombre }} {{ $cliente->paterno }}</h1>
    <div class="d-flex gap-2">
        @can('clientes.editar')
        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-gold">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endcan
        <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header py-2"><i class="bi bi-info-circle me-1"></i>Datos Personales</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:40%">CI</th>
                        <td>{{ $cliente->ci }}</td>
                    </tr>
                    <tr>
                        <th>Nombre</th>
                        <td>{{ $cliente->nombre }} {{ $cliente->paterno }} {{ $cliente->materno }}</td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td>{{ $cliente->telefono ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Correo</th>
                        <td>{{ $cliente->correo ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Dirección</th>
                        <td>{{ $cliente->direccion ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td><span class="badge badge-{{ $cliente->estado }}">{{ ucfirst($cliente->estado) }}</span></td>
                    </tr>
                    <tr>
                        <th>Registrado</th>
                        <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header py-2"><i class="bi bi-file-earmark-text me-1"></i>Contratos ({{ $cliente->contratos->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Espacio</th>
                            <th>Monto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cliente->contratos as $contrato)
                        <tr>
                            <td>{{ $contrato->fecha_contrato->format('d/m/Y') }}</td>
                            <td>#{{ $contrato->espacio_id }}</td>
                            <td>{{ number_format($contrato->monto_base, 2) }} {{ $contrato->moneda }}</td>
                            <td><span class="badge badge-{{ $contrato->estado }}">{{ ucfirst($contrato->estado) }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-2">Sin contratos</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header py-2"><i class="bi bi-cart-check me-1"></i>Ventas ({{ $cliente->ventas->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Total</th>
                            <th>Moneda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cliente->ventas as $venta)
                        <tr>
                            <td>{{ $venta->fecha_venta->format('d/m/Y') }}</td>
                            <td>{{ ucfirst($venta->tipo_venta) }}</td>
                            <td>{{ number_format($venta->precio_total, 2) }}</td>
                            <td>{{ $venta->moneda }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-2">Sin ventas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection