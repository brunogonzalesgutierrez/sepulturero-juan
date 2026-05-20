<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espacios Disponibles — El Sepulturero Juan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v=2">
    <style>
        body { background: #1a1a2e; color: #e0d6c8; font-family: 'Lato', sans-serif; }

        .page-hero {
            background: linear-gradient(135deg, #0d1220 0%, #1a1a2e 100%);
            border-bottom: 1px solid rgba(201,168,76,0.2);
            padding: 3rem 2rem 2rem;
            text-align: center;
        }

        .page-hero h1 { font-family: 'Cinzel', serif; color: #c9a84c; font-size: 2rem; margin-bottom: 0.5rem; }
        .page-hero p { color: #8a8a9a; font-size: 0.95rem; }

        .filtros {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin: 1.5rem 0;
        }

        .filtro-btn {
            background: rgba(201,168,76,0.08);
            border: 1px solid rgba(201,168,76,0.25);
            color: #c9a84c;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .filtro-btn:hover, .filtro-btn.active {
            background: #c9a84c;
            color: #1a1a2e;
            font-weight: 700;
        }

        .container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 3rem; }

        .tabla-wrap {
            background: #16213e;
            border: 1px solid rgba(201,168,76,0.2);
            border-radius: 12px;
            overflow: hidden;
        }

        table { width: 100%; border-collapse: collapse; }

        thead th {
            background: #0d1220;
            color: #c9a84c;
            padding: 0.9rem 1rem;
            text-align: left;
            font-size: 0.8rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid rgba(201,168,76,0.08);
            font-size: 0.9rem;
            color: #e0d6c8;
        }

        tbody tr:hover { background: rgba(201,168,76,0.05); }

        .badge-tipo {
            background: rgba(201,168,76,0.12);
            border: 1px solid rgba(201,168,76,0.3);
            color: #c9a84c;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            text-transform: capitalize;
        }

        .precio { color: #4ade80; font-weight: 700; }

        .empty {
            text-align: center;
            padding: 3rem;
            color: #8a8a9a;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #c9a84c;
            text-decoration: none;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }

        .back-btn:hover { color: #e8c96a; }

        .cta-box {
            background: rgba(201,168,76,0.06);
            border: 1px solid rgba(201,168,76,0.2);
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            margin-top: 2rem;
        }

        .cta-box p { color: #8a8a9a; margin-bottom: 1rem; }

        .btn-gold {
            background: #c9a84c;
            color: #1a1a2e;
            font-weight: 700;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-family: 'Cinzel', serif;
            font-size: 0.85rem;
        }

        .btn-gold:hover { background: #e8c96a; }
    </style>
</head>
<body>

<div class="page-hero">
    <h1><i class="fas fa-monument" style="font-size:1.5rem; margin-right:0.5rem;"></i>Espacios Disponibles</h1>
    <p>Encuentre el espacio ideal para su familia</p>

    <div class="filtros">
        <a href="{{ route('home.espacios') }}" class="filtro-btn {{ !$tipo ? 'active' : '' }}">Todos</a>
        <a href="{{ route('home.espacios', ['tipo' => 'nicho']) }}" class="filtro-btn {{ $tipo === 'nicho' ? 'active' : '' }}">Nichos</a>
        <a href="{{ route('home.espacios', ['tipo' => 'mausoleo']) }}" class="filtro-btn {{ $tipo === 'mausoleo' ? 'active' : '' }}">Mausoleos</a>
        <a href="{{ route('home.espacios', ['tipo' => 'lote']) }}" class="filtro-btn {{ $tipo === 'lote' ? 'active' : '' }}">Lotes Familiares</a>
        <a href="{{ route('home.espacios', ['tipo' => 'individual']) }}" class="filtro-btn {{ $tipo === 'individual' ? 'active' : '' }}">Individuales</a>
    </div>
</div>

<div class="container" style="margin-top: 2rem;">
    <a href="{{ route('home') }}#espacios" class="back-btn">
        <i class="fas fa-arrow-left"></i> Volver al inicio
    </a>

    @if($espacios->isEmpty())
        <div class="tabla-wrap">
            <div class="empty">
                <i class="fas fa-search" style="font-size:2rem; color:#c9a84c; margin-bottom:1rem;"></i>
                <p>No hay espacios disponibles para este tipo en este momento.</p>
            </div>
        </div>
    @else
        <div class="tabla-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Cementerio</th>
                        <th>Sección</th>
                        <th>Fila</th>
                        <th>Número</th>
                        <th>Ancho (m)</th>
                        <th>Largo (m)</th>
                        <th>Área (m²)</th>
                        <th>Precio/m²</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($espacios as $e)
                    <tr>
                        <td>{{ $e->id }}</td>
                        <td><span class="badge-tipo">{{ $e->tipoInhumacion->nombre ?? '—' }}</span></td>
                        <td>{{ $e->cementerio->nombre ?? '—' }}</td>
                        <td>{{ $e->direccion->seccion ?? '—' }}</td>
                        <td>{{ $e->direccion->fila ?? '—' }}</td>
                        <td>{{ $e->direccion->numero ?? '—' }}</td>
                        <td>{{ $e->dimension->ancho ?? '—' }}</td>
                        <td>{{ $e->dimension->largo ?? '—' }}</td>
                        <td>{{ $e->dimension->area ?? '—' }}</td>
                        <td class="precio">Bs. {{ number_format($e->tipoInhumacion->precio_m2 ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="cta-box">
        <p>¿Le interesa alguno de estos espacios? Contáctenos para más información o inicie sesión para gestionar su contrato.</p>
        <a href="{{ route('home') }}#contacto" class="btn-gold">
            <i class="fas fa-envelope"></i> Contáctenos
        </a>
    </div>
</div>

</body>
</html>