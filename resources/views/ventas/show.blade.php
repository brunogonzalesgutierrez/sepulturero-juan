@extends('layouts.app')
@section('title', 'Detalle Venta')
@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="bi bi-cart-check me-2"></i>Venta #{{ $venta->id }}
    </h1>

    <div class="d-flex gap-2">

        <a href="{{ route('contratos.show', $venta->contrato) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-file-earmark-text me-1"></i>Ver Contrato
        </a>

        <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>

        {{-- ✅ BOTÓN ENVIAR FACTURA --}}
        <form method="POST" action="{{ route('ventas.enviarFactura', $venta) }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary"
                onclick="return confirm('¿Enviar factura a {{ $venta->cliente->correo ?? 'sin correo' }}?')">
                <i class="bi bi-envelope me-1"></i>Enviar Factura
            </button>
        </form>

    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header py-2"><i class="bi bi-info-circle me-1"></i>Datos de la Venta</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:45%">Tipo</th>
                        <td><span class="badge {{ $venta->tipo_venta=='contado' ? 'bg-success':'bg-warning text-dark' }}">
                                {{ ucfirst($venta->tipo_venta) }}
                            </span></td>
                    </tr>
                    <tr>
                        <th>Fecha</th>
                        <td>{{ $venta->fecha_venta->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td><strong>{{ number_format($venta->precio_total, 2) }} {{ $venta->moneda }}</strong></td>
                    </tr>
                    <tr>
                        <th>Vendedor</th>
                        <td>{{ $venta->empleado->nombre }} {{ $venta->empleado->paterno }}</td>
                    </tr>
                    <tr>
                        <th>Cliente</th>
                        <td>{{ $venta->cliente->nombre }} {{ $venta->cliente->paterno }}</td>
                    </tr>
                    <tr>
                        <th>CI Cliente</th>
                        <td>{{ $venta->cliente->ci }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($venta->pagoContado)
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-cash me-1"></i>Pago al Contado</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:45%">Método</th>
                        <td>{{ ucfirst($venta->pagoContado->metodo_pago) }}</td>
                    </tr>
                    <tr>
                        <th>Descuento</th>
                        <td>{{ number_format($venta->pagoContado->descuento, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Total Pagado</th>
                        <td><strong>{{ number_format($venta->precio_total - $venta->pagoContado->descuento, 2) }} {{ $venta->moneda }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
        @endif

        @if($venta->pagoCredito)
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-percent me-1"></i>Datos de Crédito</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:50%">Interés</th>
                        <td>{{ $venta->pagoCredito->interes }}%</td>
                    </tr>
                    <tr>
                        <th>Monto con Interés</th>
                        <td><strong>{{ number_format($venta->pagoCredito->monto_inicial, 2) }}</strong></td>
                    </tr>
                    @if($venta->pagoCredito->planPago)
                    <tr>
                        <th>Frecuencia</th>
                        <td>{{ ucfirst($venta->pagoCredito->planPago->frecuencia) }}</td>
                    </tr>
                    <tr>
                        <th>Total Cuotas</th>
                        <td>{{ $venta->pagoCredito->planPago->cuotas->count() }}</td>
                    </tr>
                    <tr>
                        <th>Monto/Cuota</th>
                        <td>{{ number_format($venta->pagoCredito->planPago->monto, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Cuotas Pagadas</th>
                        <td>{{ $venta->pagoCredito->planPago->cuotas->where('estado','pagada')->count() }}</td>
                    </tr>
                    <tr>
                        <th>Cuotas Vencidas</th>
                        <td class="text-danger">{{ $venta->pagoCredito->planPago->cuotas->where('estado','vencida')->count() }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Tabla de cuotas --}}
    @if($venta->pagoCredito?->planPago)
    <div class="col-md-7">
        <div class="card">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-check me-1"></i>Plan de Pagos — Cuotas</span>
                @can('pagos.crear')
                <a href="{{ route('pagos.index') }}?venta_id={{ $venta->id }}" class="btn btn-sm btn-gold py-0 px-2">
                    <i class="bi bi-cash-coin me-1"></i>Registrar Pago
                </a>
                @endcan
            </div>
            <div class="card-body p-0" style="max-height:500px; overflow-y:auto;">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Cuota</th>
                            <th>Vence</th>
                            <th>Monto</th>
                            <th>Pagado</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->pagoCredito->planPago->cuotas as $cuota)
                        <tr class="{{ $cuota->estado == 'vencida' ? 'table-danger' : ($cuota->estado == 'pagada' ? 'table-success' : '') }}">
                            <td>#{{ $cuota->nro_cuota }}</td>
                            <td>{{ $cuota->fecha_vencimiento->format('d/m/Y') }}</td>
                            <td>{{ number_format($cuota->monto, 2) }}</td>
                            <td>
                                @if($cuota->pagos->sum('monto_pagado') > 0)
                                {{ number_format($cuota->pagos->sum('monto_pagado'), 2) }}
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $cuota->estado }}">
                                    {{ ucfirst($cuota->estado) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection