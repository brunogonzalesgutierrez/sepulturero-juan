@extends('layouts.app')
@section('title', 'Editar Cementerio')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-pencil-square me-2"></i>Editar Cementerio</h1>
    <a href="{{ route('cementerios.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>
<div class="card" style="max-width:720px;">
    <div class="card-header py-2">{{ $cementerio->nombre }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('cementerios.update', $cementerio) }}">
            @csrf @method('PUT')
            @include('cementerios._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-1"></i>Actualizar</button>
                <a href="{{ route('cementerios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection