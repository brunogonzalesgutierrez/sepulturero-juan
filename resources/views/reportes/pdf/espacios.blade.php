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
    </style>
</head>
<body>
    <h1>Estado de Espacios — El Sepulturero Juan</h1>
    <p style="font-size:10px; color:#666;">Generado: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cementerio</th>
                <th>Tipo</th>
                <th>Ancho (m)</th>
                <th>Largo (m)</th>
                <th>Área (m²)</th>
                <th>Estado</th>
                <th>Precio m²</th>
                <th>Cap. Máx.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($espacios as $e)
            <tr>
                <td>{{ $e->id }}</td>
                <td>{{ $e->cementerio->nombre }}</td>
                <td>{{ $e->tipoInhumacion->nombre }}</td>
                <td>{{ number_format($e->dimension->ancho, 2) }}</td>
                <td>{{ number_format($e->dimension->largo, 2) }}</td>
                <td>{{ number_format($e->dimension->area, 2) }}</td>
                <td>{{ ucfirst($e->estado) }}</td>
                <td>{{ number_format($e->tipoInhumacion->precio_m2, 2) }}</td>
                <td>{{ $e->tipoInhumacion->capacidad_max }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>