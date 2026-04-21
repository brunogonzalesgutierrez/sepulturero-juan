@extends('layouts.app')
@section('title', 'Detalle Espacio')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-grid-3x3-gap me-2"></i>Espacio #{{ $espacio->id }}</h1>
    <div class="d-flex gap-2">
        @can('espacios.editar')
        <a href="{{ route('espacios.edit', $espacio) }}" class="btn btn-sm btn-gold">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endcan
        <a href="{{ route('espacios.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header py-2"><i class="bi bi-info-circle me-1"></i>Datos del Espacio</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:45%">Cementerio</th>
                        <td>{{ $espacio->cementerio->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Tipo</th>
                        <td>{{ $espacio->tipoInhumacion->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Dimensión</th>
                        <td>{{ $espacio->dimension->ancho }}m × {{ $espacio->dimension->largo }}m
                            = {{ $espacio->dimension->area }} m²</td>
                    </tr>
                    <tr>
                        <th>Precio m²</th>
                        <td>{{ number_format($espacio->precio_m2, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Precio Total</th>
                        <td><strong>{{ number_format($espacio->precio_m2 * $espacio->dimension->area, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td><span class="badge badge-{{ $espacio->estado }}">{{ ucfirst($espacio->estado) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-map me-1"></i>Ubicación</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:45%">Sección</th>
                        <td>{{ $espacio->direccion->seccion ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Fila</th>
                        <td>{{ $espacio->direccion->fila ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Número</th>
                        <td>{{ $espacio->direccion->numero ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Calle/Pasillo</th>
                        <td>{{ $espacio->direccion->calle ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header py-2"><i class="bi bi-flower1 me-1"></i>Inhumaciones ({{ $espacio->inhumaciones->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>F. Defunción</th>
                            <th>F. Inhumación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($espacio->inhumaciones as $inh)
                        <tr>
                            <td>{{ $inh->nombre }} {{ $inh->paterno }}</td>
                            <td>{{ $inh->fecha_defuncion->format('d/m/Y') }}</td>
                            <td>{{ $inh->fecha_inhumacion->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-2">Sin inhumaciones</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-tools me-1"></i>Mantenimientos ({{ $espacio->mantenimientos->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($espacio->mantenimientos as $m)
                        <tr>
                            <td>{{ ucfirst($m->tipo) }}</td>
                            <td>{{ Str::limit($m->descripcion, 40) }}</td>
                            <td>{{ number_format($m->precio, 2) }}</td>
                            <td><span class="badge badge-{{ $m->estado }}">{{ ucfirst($m->estado) }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-2">Sin mantenimientos</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection