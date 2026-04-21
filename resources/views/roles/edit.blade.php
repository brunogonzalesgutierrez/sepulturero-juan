@extends('layouts.app')
@section('title', 'Editar Rol')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-shield-lock me-2"></i>Editar Rol: {{ $role->name }}</h1>
    <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>
<div class="card">
    <div class="card-header py-2">{{ $role->name }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('roles.update', $role) }}">
            @csrf @method('PUT')
            @include('roles._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-1"></i>Actualizar</button>
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection