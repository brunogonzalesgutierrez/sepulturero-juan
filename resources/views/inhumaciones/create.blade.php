@extends('layouts.app')
@section('title', 'Nueva Inhumación')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-plus-circle me-2"></i>Nueva Inhumación</h1>
    <a href="{{ route('inhumaciones.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>
<div class="card">
    <div class="card-header py-2">Datos del fallecido</div>
    <div class="card-body">
        <form method="POST" action="{{ route('inhumaciones.store') }}">
            @csrf
            @include('inhumaciones._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-1"></i>Registrar Inhumación</button>
                <a href="{{ route('inhumaciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection