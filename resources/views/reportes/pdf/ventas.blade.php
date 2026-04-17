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
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead th {
            background: #1a1a2e;
            color: #c9a84c;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }

        tbody td {
            padding: 5px 8px;
            border-bottom: 1px solid #e0d6c8;
        }

        tbody tr:nth-child(even) {
            background: #f9f6f0;
        }

        tfoot td {
            background: #1a1a2e;
            color: #fff;
            padding: 6px 8px;
            font-weight: bold;
        }

        .resumen {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .resumen-box {
            border: 1px solid #c9a84c;
            border-radius: 6px;
            padding: 10px 16px;
        }

        .resumen-box .num {
            font-size: 18px;
            font-weight: bold;
            color: #1a1a2e;
        }

        .resumen-box .lbl {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .badge-contado {
            background: #198754;
            color: #fff;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .badge-credito {
            background: #e6a800;
            color: #fff;
            padding: 2px 6px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <h1>📊 Reporte de Ventas — El Sepulturero Juan</h1>
    <p class="subtitle">Período: {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }} | Generado: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <tr>
            <td style="width:30%;">
                <div class="resumen-box">
                    <div class="num">{{ $ventas->count() }}</div>
                    <div class="lbl">Total Ventas</div>
                </div>
            </td>
            <td style="width:35%;">
                <div class="resumen-box">
                    <div class="num">{{ number_format($totalContado, 2) }}</div>
                    <div class="lbl">Contado</div>
                </div>
            </td>
            <td style="width:35%;">
                <div class="resumen-box">
                    <div class="num">{{ number_format($totalCredito, 2) }}</div>
                    <div class="lbl">Crédito</div>
                </div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>CI</th>
                <th>Tipo</th>
                <th>Total</th>
                <th>Moneda</th>
                <th>Vendedor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $v)
            <tr>
                <td>{{ $v->id }}</td>
                <td>{{ $v->fecha_venta->format('d/m/Y') }}</td>
                <td>{{ $v->cliente->nombre }} {{ $v->cliente->paterno }}</td>
                <td>{{ $v->cliente->ci }}</td>
                <td><span class="badge-{{ $v->tipo_venta }}">{{ ucfirst($v->tipo_venta) }}</span></td>
                <td>{{ number_format($v->precio_total, 2) }}</td>
                <td>{{ $v->moneda }}</td>
                <td>{{ $v->empleado->nombre }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">TOTAL</td>
                <td>{{ number_format($ventas->sum('precio_total'), 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>