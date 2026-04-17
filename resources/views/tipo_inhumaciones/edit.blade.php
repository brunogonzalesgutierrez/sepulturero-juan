@extends('layouts.app')
@section('title', 'Editar Tipo de Espacio')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-pencil-square me-2"></i>Editar Tipo</h1>
    <a href="{{ route('tipo_inhumaciones.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>
<div class="card" style="max-width:720px;">
    <div class="card-header py-2">{{ $tipoInhumacion->nombre }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('tipo_inhumaciones.update', $tipoInhumacion) }}">
            @csrf @method('PUT')
            @include('tipo_inhumaciones._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-1"></i>Actualizar</button>
                <a href="{{ route('tipo_inhumaciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection