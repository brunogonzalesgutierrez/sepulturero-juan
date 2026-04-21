@extends('layouts.app')
@section('title', 'Editar Cliente')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-pencil-square me-2"></i>Editar Cliente</h1>
    <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card" style="max-width:680px;">
    <div class="card-header py-2">Editar datos de: <strong>{{ $cliente->nombre }} {{ $cliente->paterno }}</strong></div>
    <div class="card-body">
        <form method="POST" action="{{ route('clientes.update', $cliente) }}">
            @csrf @method('PUT')
            @include('clientes._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-gold">
                    <i class="bi bi-save me-1"></i>Actualizar Cliente
                </button>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection