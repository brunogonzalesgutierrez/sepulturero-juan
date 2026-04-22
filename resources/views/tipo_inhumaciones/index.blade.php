@extends('layouts.app')
@section('title', 'Tipos de Espacio')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-tags me-2"></i>Tipos de Espacio / Inhumación</h1>
    @can('cementerios.ver')
    <a href="{{ route('tipo_inhumaciones.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Tipo
    </a>
    @endcan
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Precio Base</th>
                    <th>Cap. Máx</th>
                    <th>Área Base</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tipos as $t)
                <tr>
                    <td class="text-muted" style="font-size:0.8rem;">{{ $t->id }}</td>
                    <td><strong>{{ $t->nombre }}</strong></td>
                    <td>{{ number_format($t->precio, 2) }}</td>
                    <td>{{ number_format($t->precio_base, 2) }}</td>
                    <td>{{ $t->capacidad_max }}</td>
                    <td>{{ $t->area_base }} m²</td>
                    <td><span class="badge badge-{{ $t->estado }}">{{ ucfirst($t->estado) }}</span></td>
                    <td class="text-center">
                        @can('cementerios.ver')
                        <a href="{{ route('tipo_inhumaciones.edit', $t->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @endcan
                        @can('cementerios.ver')
                        <form method="POST" action="{{ route('tipo_inhumaciones.destroy', $t->id) }}" class="d-inline"
                            onsubmit="return confirm('¿Eliminar {{ addslashes($t->nombre) }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay tipos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tipos->hasPages())
    <div class="card-footer py-2">{{ $tipos->links() }}</div>
    @endif
</div>
@endsection