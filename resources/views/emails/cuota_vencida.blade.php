@component('mail::message')
# Aviso de Cuota Vencida

@php
    $cliente = $cuota->planPago->pagoCredito->venta->cliente;
    $contrato = $cuota->planPago->pagoCredito->venta->contrato;
@endphp

Estimado/a **{{ $cliente->nombre }} {{ $cliente->paterno }}**,

Le informamos que tiene una cuota **vencida** pendiente de pago.

@component('mail::table')
| Concepto | Detalle |
|---|---|
| Cuota | #{{ $cuota->nro_cuota }} |
| Fecha de vencimiento | {{ $cuota->fecha_vencimiento->format('d/m/Y') }} |
| Días vencida | {{ $cuota->fecha_vencimiento->diffInDays(now()) }} días |
| Monto pendiente | {{ number_format($cuota->monto, 2) }} |
| Contrato | #{{ $contrato->id }} |
@endcomponent

@component('mail::panel')
Por favor acérquese a nuestras oficinas o comuníquese con nosotros para regularizar su situación.
@endcomponent

Atentamente,<br>
**El Sepulturero Juan**
@endcomponent