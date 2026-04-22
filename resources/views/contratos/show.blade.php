@extends('layouts.app')
@section('title', 'Detalle Contrato')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-file-earmark-text me-2"></i>Contrato #{{ $contrato->id }}</h1>
    <div class="d-flex gap-2">
        @if(!$contrato->venta)
        @can('ventas.ver')
        <a href="{{ route('ventas.create') }}?contrato_id={{ $contrato->id }}" class="btn btn-sm btn-gold">
            <i class="bi bi-cart-plus me-1"></i>Registrar Venta
        </a>
        @endcan
        @endif
        @can('contratos.ver')
        <a href="{{ route('contratos.edit', $contrato) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endcan
        <a href="{{ route('contratos.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Info contrato --}}
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header py-2"><i class="bi bi-info-circle me-1"></i>Datos del Contrato</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:50%">Fecha</th>
                        <td>{{ $contrato->fecha_contrato->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Monto Base</th>
                        <td>{{ number_format($contrato->monto_base, 2) }} {{ $contrato->moneda }}</td>
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
                        <th>Estado</th>
                        <td><span class="badge badge-{{ $contrato->estado }}">{{ ucfirst($contrato->estado) }}</span></td>
                    </tr>
                    @if($contrato->observacion)
                    <tr>
                        <th>Obs.</th>
                        <td>{{ $contrato->observacion }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header py-2"><i class="bi bi-person me-1"></i>Cliente</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:40%">Nombre</th>
                        <td>{{ $contrato->cliente->nombre }} {{ $contrato->cliente->paterno }}</td>
                    </tr>
                    <tr>
                        <th>CI</th>
                        <td>{{ $contrato->cliente->ci }}</td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td>{{ $contrato->cliente->telefono ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Correo</th>
                        <td>{{ $contrato->cliente->correo ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Espacio --}}
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header py-2"><i class="bi bi-grid-3x3-gap me-1"></i>Espacio</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:45%">Cementerio</th>
                        <td>{{ $contrato->espacio->cementerio->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Tipo</th>
                        <td>{{ $contrato->espacio->tipoInhumacion->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Sección</th>
                        <td>{{ $contrato->espacio->direccion->seccion ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Fila / Nro</th>
                        <td>{{ $contrato->espacio->direccion->fila ?? '—' }} / {{ $contrato->espacio->direccion->numero ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Dimensión</th>
                        <td>{{ $contrato->espacio->dimension->ancho }}m × {{ $contrato->espacio->dimension->largo }}m</td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td><span class="badge badge-{{ $contrato->espacio->estado }}">{{ ucfirst($contrato->espacio->estado) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Inhumaciones --}}
        <div class="card">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-flower1 me-1"></i>Inhumaciones ({{ $contrato->inhumaciones->count() }})</span>
                @can('inhumaciones.ver')
                <a href="{{ route('inhumaciones.create') }}" class="btn btn-sm btn-outline-secondary py-0">
                    <i class="bi bi-plus-lg"></i>
                </a>
                @endcan
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>F. Inhumación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contrato->inhumaciones as $inh)
                        <tr>
                            <td>{{ $inh->nombre }} {{ $inh->paterno }}</td>
                            <td>{{ $inh->fecha_inhumacion->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-2">Sin inhumaciones</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Venta y pagos --}}
    <div class="col-md-4">
        @if($contrato->venta)
        <div class="card mb-3">
            <div class="card-header py-2"><i class="bi bi-cart-check me-1"></i>Venta</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:50%">Tipo</th>
                        <td><span class="badge bg-dark">{{ ucfirst($contrato->venta->tipo_venta) }}</span></td>
                    </tr>
                    <tr>
                        <th>Fecha</th>
                        <td>{{ $contrato->venta->fecha_venta->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>{{ number_format($contrato->venta->precio_total, 2) }} {{ $contrato->venta->moneda }}</td>
                    </tr>
                </table>
                <a href="{{ route('ventas.show', $contrato->venta) }}" class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="bi bi-eye me-1"></i>Ver venta completa
                </a>
            </div>
        </div>

        @if($contrato->venta->pagoCredito?->planPago)
        @php $plan = $contrato->venta->pagoCredito->planPago; @endphp
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-calendar-check me-1"></i>Plan de Pagos</div>
            <div class="card-body p-0">
                <div class="px-3 py-2 border-bottom">
                    <small class="text-muted">
                        {{ ucfirst($plan->frecuencia) }} |
                        {{ $plan->cuotas->count() }} cuotas |
                        Interés: {{ $contrato->venta->pagoCredito->interes }}%
                    </small>
                </div>
                <div style="max-height:250px; overflow-y:auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Cuota</th>
                                <th>Vence</th>
                                <th>Monto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plan->cuotas->take(10) as $cuota)
                            <tr>
                                <td>#{{ $cuota->nro_cuota }}</td>
                                <td>{{ $cuota->fecha_vencimiento->format('d/m/Y') }}</td>
                                <td>{{ number_format($cuota->monto, 2) }}</td>
                                <td><span class="badge badge-{{ $cuota->estado }}">{{ ucfirst($cuota->estado) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($plan->cuotas->count() > 10)
                <div class="px-3 py-2">
                    <a href="{{ route('ventas.show', $contrato->venta) }}" class="btn btn-sm btn-outline-secondary w-100">
                        Ver todas las cuotas ({{ $plan->cuotas->count() }})
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif

        @else
        <div class="card">
            <div class="card-body text-center py-4">
                <i class="bi bi-cart-x fs-2 text-muted d-block mb-2"></i>
                <p class="text-muted mb-3">Este contrato no tiene venta registrada.</p>
                @can('ventas.ver')
                <a href="{{ route('ventas.create') }}?contrato_id={{ $contrato->id }}" class="btn btn-gold">
                    <i class="bi bi-cart-plus me-1"></i>Registrar Venta
                </a>
                @endcan
            </div>
        </div>
        @endif
    </div>
</div>
@endsection