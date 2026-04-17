@extends('layouts.app')
@section('title', 'Detalle Inhumación')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-flower1 me-2"></i>{{ $inhumacion->nombre }} {{ $inhumacion->paterno }}</h1>
    <div class="d-flex gap-2">
        @can('inhumaciones.editar')
        <a href="{{ route('inhumaciones.edit', $inhumacion) }}" class="btn btn-sm btn-gold">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endcan
        <a href="{{ route('inhumaciones.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-person me-1"></i>Datos del Fallecido</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:45%">Nombre completo</th>
                        <td>{{ $inhumacion->nombre }} {{ $inhumacion->paterno }} {{ $inhumacion->materno }}</td>
                    </tr>
                    <tr>
                        <th>F. Nacimiento</th>
                        <td>{{ $inhumacion->fecha_nacimiento?->format('d/m/Y') ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>F. Defunción</th>
                        <td>{{ $inhumacion->fecha_defuncion->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>F. Inhumación</th>
                        <td>{{ $inhumacion->fecha_inhumacion->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Causa de muerte</th>
                        <td>{{ $inhumacion->causa_muerte ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header py-2"><i class="bi bi-grid-3x3-gap me-1"></i>Espacio Asignado</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:45%">Cementerio</th>
                        <td>{{ $inhumacion->espacio->cementerio->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Tipo</th>
                        <td>{{ $inhumacion->espacio->tipoInhumacion->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Sección</th>
                        <td>{{ $inhumacion->espacio->direccion->seccion ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Fila / Nro</th>
                        <td>{{ $inhumacion->espacio->direccion->fila ?? '—' }} / {{ $inhumacion->espacio->direccion->numero ?? '—' }}</td>
                    </tr>
                </table>
                <a href="{{ route('espacios.show', $inhumacion->espacio) }}" class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="bi bi-eye me-1"></i>Ver espacio
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-header py-2"><i class="bi bi-file-earmark-text me-1"></i>Contrato y Titular</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th style="width:45%">Contrato #</th>
                        <td>{{ $inhumacion->contrato_id }}</td>
                    </tr>
                    <tr>
                        <th>Titular</th>
                        <td>{{ $inhumacion->contrato->cliente->nombre }} {{ $inhumacion->contrato->cliente->paterno }}</td>
                    </tr>
                    <tr>
                        <th>CI Titular</th>
                        <td>{{ $inhumacion->contrato->cliente->ci }}</td>
                    </tr>
                    <tr>
                        <th>Estado contrato</th>
                        <td><span class="badge badge-{{ $inhumacion->contrato->estado }}">
                                {{ ucfirst($inhumacion->contrato->estado) }}
                            </span></td>
                    </tr>
                </table>
                <a href="{{ route('contratos.show', $inhumacion->contrato) }}" class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="bi bi-eye me-1"></i>Ver contrato
                </a>
            </div>
        </div>
    </div>
</div>
@endsection