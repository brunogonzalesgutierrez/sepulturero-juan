<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h1 {
            font-size: 15px;
            border-bottom: 2px solid #c9a84c;
            padding-bottom: 5px;
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
    </style>
</head>

<body>
    <h1>Contratos {{ ucfirst($estado) }} — El Sepulturero Juan</h1>
    <p style="font-size:10px; color:#666;">Saldo total pendiente: <strong>{{ number_format($totalSaldo, 2) }}</strong> | Generado: {{ now()->format('d/m/Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>CI</th>
                <th>Espacio</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Saldo</th>
                <th>Moneda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contratos as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->cliente->nombre }} {{ $c->cliente->paterno }}</td>
                <td>{{ $c->cliente->ci }}</td>
                <td>{{ $c->espacio->cementerio->nombre }}</td>
                <td>{{ $c->fecha_contrato->format('d/m/Y') }}</td>
                <td>{{ number_format($c->monto_base, 2) }}</td>
                <td>{{ number_format($c->saldo_pendiente, 2) }}</td>
                <td>{{ $c->moneda }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">TOTALES</td>
                <td>{{ number_format($totalSaldo, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>