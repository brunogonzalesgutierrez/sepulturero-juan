@extends('layouts.app')
@section('title', 'Nuevo Mantenimiento')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-plus-circle me-2"></i>Nuevo Mantenimiento</h1>
    <a href="{{ route('mantenimientos.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>
<div class="card">
    <div class="card-header py-2">Datos del mantenimiento</div>
    <div class="card-body">
        <form method="POST" action="{{ route('mantenimientos.store') }}">
            @csrf
            @include('mantenimientos._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-1"></i>Guardar</button>
                <a href="{{ route('mantenimientos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection