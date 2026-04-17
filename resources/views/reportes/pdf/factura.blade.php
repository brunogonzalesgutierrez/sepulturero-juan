<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size:11px; color:#1a1a2e; padding:20px; }
    .header { display:table; width:100%; border-bottom:3px solid #c9a84c; padding-bottom:12px; margin-bottom:15px; }
    .header-left { display:table-cell; vertical-align:middle; }
    .header-right { display:table-cell; text-align:right; vertical-align:middle; }
    .empresa { font-size:18px; font-weight:bold; color:#1a1a2e; }
    .factura-num { font-size:22px; font-weight:bold; color:#c9a84c; }
    .factura-label { font-size:10px; color:#666; text-transform:uppercase; letter-spacing:1px; }
    .row-info { display:table; width:100%; margin-bottom:15px; }
    .col-info { display:table-cell; width:50%; vertical-align:top; }
    .box { background:#f9f6f0; border:1px solid #e0d6c8; border-radius:6px; padding:10px 12px; }
    .box-title { font-weight:bold; color:#c9a84c; font-size:9px; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; }
    .box-row { margin-bottom:3px; }
    .box-label { color:#666; }
    table.items { width:100%; border-collapse:collapse; margin:15px 0; }
    table.items thead th { background:#1a1a2e; color:#c9a84c; padding:7px 10px; font-size:10px; text-align:left; }
    table.items tbody td { padding:6px 10px; border-bottom:1px solid #e0d6c8; }
    table.items tbody tr:nth-child(even) { background:#f9f6f0; }
    .totales { text-align:right; margin-top:5px; }
    .total-row { display:inline-block; min-width:250px; text-align:right; }
    .total-final { background:#1a1a2e; color:#c9a84c; padding:8px 15px; border-radius:4px; font-size:14px; font-weight:bold; margin-top:8px; }
    .footer { margin-top:25px; padding-top:10px; border-top:1px solid #e0d6c8; text-align:center; color:#888; font-size:9px; }
    .badge { padding:3px 8px; border-radius:4px; font-size:10px; font-weight:bold; }
    .badge-contado { background:#d4edda; color:#155724; }
    .badge-credito  { background:#fff3cd; color:#856404; }
    .plan-box { background:#f0f4ff; border:1px solid #c9a84c; border-radius:6px; padding:10px; margin-top:12px; }
</style>
</head>
<body>

<div class="header">
    <div class="header-left">
        <div class="empresa">🏛️ Cementerio El Sepulturero Juan</div>
        <div style="color:#666; font-size:10px; margin-top:3px;">Sistema de Gestión de Espacios Funerarios</div>
    </div>
    <div class="header-right">
        <div class="factura-label">Factura de Venta</div>
        <div class="factura-num">#{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</div>
        <div style="font-size:10px; color:#666;">{{ $venta->fecha_venta->format('d/m/Y') }}</div>
    </div>
</div>

<div class="row-info">
    <div class="col-info" style="padding-right:10px;">
        <div class="box">
            <div class="box-title">Cliente</div>
            <div class="box-row"><strong>{{ $venta->cliente->nombre }} {{ $venta->cliente->paterno }} {{ $venta->cliente->materno }}</strong></div>
            <div class="box-row"><span class="box-label">CI:</span> {{ $venta->cliente->ci }}</div>
            <div class="box-row"><span class="box-label">Tel:</span> {{ $venta->cliente->telefono ?? '—' }}</div>
            <div class="box-row"><span class="box-label">Correo:</span> {{ $venta->cliente->correo ?? '—' }}</div>
            <div class="box-row"><span class="box-label">Dirección:</span> {{ $venta->cliente->direccion ?? '—' }}</div>
        </div>
    </div>
    <div class="col-info" style="padding-left:10px;">
        <div class="box">
            <div class="box-title">Datos de la Venta</div>
            <div class="box-row"><span class="box-label">Contrato:</span> #{{ $venta->contrato_id }}</div>
            <div class="box-row"><span class="box-label">Tipo:</span>
                <span class="badge badge-{{ $venta->tipo_venta }}">{{ ucfirst($venta->tipo_venta) }}</span>
            </div>
            <div class="box-row"><span class="box-label">Moneda:</span> {{ $venta->moneda }}</div>
            <div class="box-row"><span class="box-label">Vendedor:</span> {{ $venta->empleado->nombre }} {{ $venta->empleado->paterno }}</div>
            <div class="box-row"><span class="box-label">Fecha:</span> {{ $venta->fecha_venta->format('d/m/Y') }}</div>
        </div>
    </div>
</div>

<table class="items">
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Cementerio</th>
            <th>Ubicación</th>
            <th>Dimensión</th>
            <th style="text-align:right;">Precio</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <strong>{{ $venta->contrato->espacio->tipoInhumacion->nombre }}</strong><br>
                <small style="color:#666;">Espacio #{{ $venta->contrato->espacio_id }}</small>
            </td>
            <td>{{ $venta->contrato->espacio->cementerio->nombre }}</td>
            <td>
                Secc: {{ $venta->contrato->espacio->direccion->seccion ?? '—' }}<br>
                Fila: {{ $venta->contrato->espacio->direccion->fila ?? '—' }}
                Nro: {{ $venta->contrato->espacio->direccion->numero ?? '—' }}
            </td>
            <td>
                {{ $venta->contrato->espacio->dimension->ancho ?? '?' }}m ×
                {{ $venta->contrato->espacio->dimension->largo ?? '?' }}m
            </td>
            <td style="text-align:right;">
                <strong>{{ number_format($venta->precio_total, 2) }}</strong>
            </td>
        </tr>
    </tbody>
</table>

<div class="totales">
    <div class="total-row">
        <table style="width:100%; font-size:11px;">
            <tr><td style="color:#666; padding:3px 8px;">Subtotal:</td>
                <td style="text-align:right; padding:3px 8px;">{{ number_format($venta->precio_total, 2) }} {{ $venta->moneda }}</td>
            </tr>
            @if($venta->pagoContado && $venta->pagoContado->descuento > 0)
            <tr><td style="color:#198754; padding:3px 8px;">Descuento:</td>
                <td style="text-align:right; padding:3px 8px; color:#198754;">- {{ number_format($venta->pagoContado->descuento, 2) }}</td>
            </tr>
            @endif
            @if($venta->pagoCredito)
            <tr><td style="color:#e6a800; padding:3px 8px;">Interés ({{ $venta->pagoCredito->interes }}%):</td>
                <td style="text-align:right; padding:3px 8px; color:#e6a800;">
                    + {{ number_format($venta->pagoCredito->monto_inicial - $venta->precio_total, 2) }}
                </td>
            </tr>
            <tr><td style="padding:3px 8px; font-weight:bold;">Total con interés:</td>
                <td style="text-align:right; padding:3px 8px; font-weight:bold;">
                    {{ number_format($venta->pagoCredito->monto_inicial, 2) }} {{ $venta->moneda }}
                </td>
            </tr>
            @endif
        </table>
        <div class="total-final">
            TOTAL: {{ number_format($venta->pagoCredito ? $venta->pagoCredito->monto_inicial : $venta->precio_total, 2) }} {{ $venta->moneda }}
        </div>
    </div>
</div>

@if($venta->pagoCredito?->planPago)
<div class="plan-box">
    <strong>Plan de Pagos:</strong>
    {{ $venta->pagoCredito->planPago->cuotas->count() }} cuotas
    {{ ucfirst($venta->pagoCredito->planPago->frecuencia) }}s de
    {{ number_format($venta->pagoCredito->planPago->monto, 2) }} {{ $venta->moneda }}
    | Inicio: {{ $venta->pagoCredito->planPago->fecha_inicio->format('d/m/Y') }}
    | Fin estimado: {{ $venta->pagoCredito->planPago->fecha_fin->format('d/m/Y') }}
</div>
@endif

@if($venta->pagoContado)
<div class="plan-box" style="background:#d4edda; border-color:#198754;">
    <strong>Pago al Contado:</strong>
    Método: {{ ucfirst($venta->pagoContado->metodo_pago) }}
    @if($venta->pagoContado->descuento > 0)
    | Descuento aplicado: {{ number_format($venta->pagoContado->descuento, 2) }}
    @endif
</div>
@endif

<div class="footer">
    Cementerio "El Sepulturero Juan" — Documento generado el {{ now()->format('d/m/Y H:i') }}
    — Este documento es válido como comprobante de venta.
</div>

</body>
</html>