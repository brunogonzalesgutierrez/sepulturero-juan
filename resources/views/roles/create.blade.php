@extends('layouts.app')
@section('title', 'Nuevo Rol')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-shield-plus me-2"></i>Nuevo Rol</h1>
    <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>
<div class="card">
    <div class="card-header py-2">Datos del rol</div>
    <div class="card-body">
        <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            @include('roles._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-1"></i>Crear Rol</button>
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection