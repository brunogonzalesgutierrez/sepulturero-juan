@extends('layouts.app')
@section('title', 'Nuevo Empleado')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-person-plus me-2"></i>Nuevo Empleado</h1>
    <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>
<div class="card" style="max-width:720px;">
    <div class="card-header py-2">Datos del empleado</div>
    <div class="card-body">
        <form method="POST" action="{{ route('empleados.store') }}">
            @csrf
            @include('empleados._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-1"></i>Guardar</button>
                <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection