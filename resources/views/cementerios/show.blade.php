@extends('layouts.app')
@section('title', 'Detalle Cementerio')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-geo-alt me-2"></i>{{ $cementerio->nombre }}</h1>
    <div class="d-flex gap-2">
        {{-- @can('cementerios.editar') --}}
        <a href="{{ route('cementerios.edit', $cementerio) }}" class="btn btn-sm btn-gold">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        {{-- @endcan --}}
        <a href="{{ route('cementerios.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-info-circle me-1"></i>Información</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:50%">Nombre</th>
                        <td>{{ $cementerio->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Tipo</th>
                        <td>{{ $cementerio->tipo_cementerio }}</td>
                    </tr>
                    <tr>
                        <th>Localización</th>
                        <td>{{ $cementerio->localizacion }}</td>
                    </tr>
                    <tr>
                        <th>Espacios Totales</th>
                        <td>{{ $cementerio->espacio_total }}</td>
                    </tr>
                    <tr>
                        <th>Disponibles</th>
                        <td>{{ $cementerio->espacios->where('estado','disponible')->count() }}</td>
                    </tr>
                    <tr>
                        <th>Ocupados</th>
                        <td>{{ $cementerio->espacios->where('estado','ocupado')->count() }}</td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td>
                            <span class="badge badge-{{ $cementerio->estado }}">
                                {{ ucfirst($cementerio->estado) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-grid-3x3-gap me-1"></i>Espacios ({{ $cementerio->espacios->count() }})</span>
                {{-- @can('espacios.crear') --}}
                <a href="{{ route('espacios.create') }}" class="btn btn-sm btn-gold">
                    <i class="bi bi-plus-lg me-1"></i>Nuevo Espacio
                </a>
                {{-- @endcan --}}
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Sección</th>
                                <th>Fila/Nro</th>
                                <th>Precio m²</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cementerio->espacios as $esp)
                            <tr>
                                <td>{{ $esp->tipoInhumacion->nombre }}</td>
                                <td>{{ $esp->direccion->seccion ?? '—' }}</td>
                                <td>{{ $esp->direccion->fila ?? '—' }} / {{ $esp->direccion->numero ?? '—' }}</td>
                                <td>{{ number_format($esp->precio_m2, 2) }}</td>
                                <td><span class="badge badge-{{ $esp->estado }}">{{ ucfirst($esp->estado) }}</span></td>
                                <td>
                                    <a href="{{ route('espacios.show', $esp) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-2">Sin espacios registrados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection