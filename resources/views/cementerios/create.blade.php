@extends('layouts.app')
@section('title', 'Nuevo Cementerio')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-plus-circle me-2"></i>Nuevo Cementerio</h1>
    <a href="{{ route('cementerios.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>
<div class="card" style="max-width:720px;">
    <div class="card-header py-2">Datos del cementerio</div>
    <div class="card-body">
        <form method="POST" action="{{ route('cementerios.store') }}">
            @csrf
            @include('cementerios._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-1"></i>Guardar</button>
                <a href="{{ route('cementerios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection