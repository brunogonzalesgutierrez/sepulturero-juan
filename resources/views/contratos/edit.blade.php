@extends('layouts.app')
@section('title', 'Editar Contrato')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-pencil-square me-2"></i>Editar Contrato #{{ $contrato->id }}</h1>
    <a href="{{ route('contratos.show', $contrato) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>
<div class="card">
    <div class="card-header py-2">{{ $contrato->cliente->nombre }} {{ $contrato->cliente->paterno }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('contratos.update', $contrato) }}">
            @csrf @method('PUT')
            @include('contratos._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-1"></i>Actualizar</button>
                <a href="{{ route('contratos.show', $contrato) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection