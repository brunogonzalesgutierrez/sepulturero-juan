@component('mail::message')
# Recibo de Pago

@php
    $cliente = $pago->cuota->planPago->pagoCredito->venta->cliente;
    $contrato = $pago->cuota->planPago->pagoCredito->venta->contrato;
@endphp

Estimado/a **{{ $cliente->nombre }} {{ $cliente->paterno }}**,

Confirmamos la recepción de su pago.

@component('mail::table')
| Concepto | Detalle |
|---|---|
| Nro. Pago | #{{ $pago->id }} |
| Fecha | {{ $pago->fecha_pago->format('d/m/Y') }} |
| Cuota | #{{ $pago->cuota->nro_cuota }} |
| Monto pagado | {{ number_format($pago->monto_pagado, 2) }} |
| Método | {{ ucfirst($pago->metodo_pago) }} |
| Comprobante | {{ $pago->comprobante ?? 'N/A' }} |
| Saldo pendiente | {{ number_format($contrato->saldo_pendiente, 2) }} |
@endcomponent

Atentamente,<br>
**El Sepulturero Juan**
@endcomponent