@component('mail::message')
# Gracias por su compra

Estimado/a **{{ $venta->cliente->nombre }} {{ $venta->cliente->paterno }}**,

Le informamos que su venta ha sido registrada exitosamente en el cementerio **El Sepulturero Juan**.

@component('mail::table')
| Concepto | Detalle |
|---|---|
| Nro. Venta | #{{ $venta->id }} |
| Fecha | {{ $venta->fecha_venta->format('d/m/Y') }} |
| Tipo | {{ ucfirst($venta->tipo_venta) }} |
| Total | {{ number_format($venta->precio_total, 2) }} {{ $venta->moneda }} |
| Espacio | {{ $venta->contrato->espacio->cementerio->nombre }} |
@endcomponent

Adjunto encontrará su factura en formato PDF.

@if($venta->tipo_venta === 'credito' && $venta->pagoCredito?->planPago)
@component('mail::panel')
**Plan de pagos:** {{ $venta->pagoCredito->planPago->cuotas->count() }} cuotas de
{{ number_format($venta->pagoCredito->planPago->monto, 2) }} {{ $venta->moneda }}
({{ ucfirst($venta->pagoCredito->planPago->frecuencia) }})
@endcomponent
@endif

Para cualquier consulta, contáctenos.

Atentamente,<br>
**El Sepulturero Juan**
@endcomponent