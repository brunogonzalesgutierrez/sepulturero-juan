@extends('layouts.app')
@section('title', 'Detalle Pago')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-receipt me-2"></i>Pago #{{ $pago->id }}</h1>
    <div class="d-flex gap-2">
        @can('pagos.editar')
        <a href="{{ route('pagos.edit', $pago) }}" class="btn btn-sm btn-gold">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endcan
        <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

@php
$cliente = $pago->cuota->planPago->pagoCredito->venta->cliente;
$venta = $pago->cuota->planPago->pagoCredito->venta;
$contrato = $venta->contrato;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header py-2"><i class="bi bi-cash me-1"></i>Datos del Pago</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:45%">Fecha</th>
                        <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Monto Pagado</th>
                        <td><strong class="text-success fs-5">{{ number_format($pago->monto_pagado, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Interés adicional</th>
                        <td>{{ number_format($pago->monto_interes, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Método</th>
                        <td>{{ ucfirst($pago->metodo_pago) }}</td>
                    </tr>
                    <tr>
                        <th>Comprobante</th>
                        <td>{{ $pago->comprobante ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Cobrado por</th>
                        <td>{{ $pago->empleado->nombre }} {{ $pago->empleado->paterno }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-calendar-check me-1"></i>Cuota</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:45%">Número</th>
                        <td>#{{ $pago->cuota->nro_cuota }}</td>
                    </tr>
                    <tr>
                        <th>Vencimiento</th>
                        <td>{{ $pago->cuota->fecha_vencimiento->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Monto cuota</th>
                        <td>{{ number_format($pago->cuota->monto, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Estado cuota</th>
                        <td><span class="badge badge-{{ $pago->cuota->estado }}">{{ ucfirst($pago->cuota->estado) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header py-2"><i class="bi bi-person me-1"></i>Cliente</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:40%">Nombre</th>
                        <td>{{ $cliente->nombre }} {{ $cliente->paterno }} {{ $cliente->materno }}</td>
                    </tr>
                    <tr>
                        <th>CI</th>
                        <td>{{ $cliente->ci }}</td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td>{{ $cliente->telefono ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-file-earmark-text me-1"></i>Contrato / Venta</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:45%">Contrato #</th>
                        <td>{{ $contrato->id }}</td>
                    </tr>
                    <tr>
                        <th>Saldo Pendiente</th>
                        <td>
                            @if($contrato->saldo_pendiente > 0)
                            <span class="text-danger fw-bold">{{ number_format($contrato->saldo_pendiente, 2) }}</span>
                            @else
                            <span class="text-success fw-bold">PAGADO</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Venta #</th>
                        <td>{{ $venta->id }}</td>
                    </tr>
                    <tr>
                        <th>Total Venta</th>
                        <td>{{ number_format($venta->precio_total, 2) }} {{ $venta->moneda }}</td>
                    </tr>
                </table>
                <div class="d-flex gap-2 mt-2">
                    <a href="{{ route('contratos.show', $contrato) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-file-earmark-text me-1"></i>Ver contrato
                    </a>
                    <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-cart-check me-1"></i>Ver venta
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection