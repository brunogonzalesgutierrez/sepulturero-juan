<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a2e;
        }

        h1 {
            font-size: 16px;
            color: #1a1a2e;
            border-bottom: 2px solid #c9a84c;
            padding-bottom: 6px;
        }

        .subtitle {
            color: #666;
            font-size: 10px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        thead th {
            background: #1a1a2e;
            color: #c9a84c;
            padding: 5px 7px;
            font-size: 10px;
        }

        tbody td {
            padding: 4px 7px;
            border-bottom: 1px solid #e0d6c8;
        }

        tbody tr:nth-child(even) {
            background: #f9f6f0;
        }

        tfoot td {
            background: #1a1a2e;
            color: #fff;
            padding: 5px 7px;
            font-weight: bold;
        }

        h2 {
            font-size: 13px;
            margin-top: 20px;
            color: #dc3545;
        }
    </style>
</head>

<body>
    <h1>💰 Reporte de Pagos — El Sepulturero Juan</h1>
    <p class="subtitle">Período: {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }} | Total cobrado: <strong>{{ number_format($totalCobrado, 2) }}</strong></p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Cuota</th>
                <th>Monto</th>
                <th>Método</th>
                <th>Comprobante</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pagos as $p)
            @php $cliente = $p->cuota->planPago->pagoCredito->venta->cliente; @endphp
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->fecha_pago->format('d/m/Y') }}</td>
                <td>{{ $cliente->nombre }} {{ $cliente->paterno }}</td>
                <td>#{{ $p->cuota->nro_cuota }}</td>
                <td>{{ number_format($p->monto_pagado, 2) }}</td>
                <td>{{ ucfirst($p->metodo_pago) }}</td>
                <td>{{ $p->comprobante ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">TOTAL COBRADO</td>
                <td>{{ number_format($totalCobrado, 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    @if($cuotasVencidas->count() > 0)
    <h2>⚠ Cuotas Vencidas ({{ $cuotasVencidas->count() }})</h2>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>CI</th>
                <th>Cuota</th>
                <th>Vencimiento</th>
                <th>Monto</th>
                <th>Días</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cuotasVencidas as $c)
            @php $cliente = $c->planPago->pagoCredito->venta->cliente; @endphp
            <tr>
                <td>{{ $cliente->nombre }} {{ $cliente->paterno }}</td>
                <td>{{ $cliente->ci }}</td>
                <td>#{{ $c->nro_cuota }}</td>
                <td>{{ $c->fecha_vencimiento->format('d/m/Y') }}</td>
                <td>{{ number_format($c->monto, 2) }}</td>
                <td>{{ $c->fecha_vencimiento->diffInDays(now()) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>

</html>