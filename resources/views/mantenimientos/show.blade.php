@extends('layouts.app')
@section('title', 'Detalle Mantenimiento')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-tools me-2"></i>Mantenimiento #{{ $mantenimiento->id }}</h1>
    <div class="d-flex gap-2">
        @can('mantenimientos.editar')
        <a href="{{ route('mantenimientos.edit', $mantenimiento) }}" class="btn btn-sm btn-gold">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endcan
        <a href="{{ route('mantenimientos.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-info-circle me-1"></i>Datos del Mantenimiento</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:40%">Espacio</th>
                        <td>#{{ $mantenimiento->espacio_id }} —
                            {{ $mantenimiento->espacio->cementerio->nombre }}<br>
                            <small>Secc: {{ $mantenimiento->espacio->direccion->seccion ?? '?' }} |
                                Fila: {{ $mantenimiento->espacio->direccion->fila ?? '?' }} |
                                Nro: {{ $mantenimiento->espacio->direccion->numero ?? '?' }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Tipo</th>
                        <td>{{ ucfirst($mantenimiento->tipo) }}</td>
                    </tr>
                    <tr>
                        <th>Descripción</th>
                        <td>{{ $mantenimiento->descripcion }}</td>
                    </tr>
                    <tr>
                        <th>Precio</th>
                        <td><strong>{{ number_format($mantenimiento->precio, 2) }} BOB</strong></td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td><span class="badge badge-{{ $mantenimiento->estado }}">
                                {{ ucfirst(str_replace('_',' ',$mantenimiento->estado)) }}
                            </span></td>
                    </tr>
                    <tr>
                        <th>Fecha Inicio</th>
                        <td>{{ $mantenimiento->fecha_inicio?->format('d/m/Y') ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Fecha Fin</th>
                        <td>{{ $mantenimiento->fecha_fin?->format('d/m/Y') ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-grid-3x3-gap me-1"></i>Espacio Asociado</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:40%">Cementerio</th>
                        <td>{{ $mantenimiento->espacio->cementerio->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Tipo</th>
                        <td>{{ $mantenimiento->espacio->tipoInhumacion->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Estado actual</th>
                        <td><span class="badge badge-{{ $mantenimiento->espacio->estado }}">
                                {{ ucfirst($mantenimiento->espacio->estado) }}
                            </span></td>
                    </tr>
                </table>
                <a href="{{ route('espacios.show', $mantenimiento->espacio) }}"
                    class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="bi bi-eye me-1"></i>Ver espacio completo
                </a>
            </div>
        </div>
    </div>
</div>
@endsection