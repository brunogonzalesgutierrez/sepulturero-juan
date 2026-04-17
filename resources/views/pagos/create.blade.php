@extends('layouts.app')
@section('title', 'Registrar Pago')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-cash-coin me-2"></i>Registrar Pago</h1>
    <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>
<div class="card" style="max-width:780px;">
    <div class="card-header py-2">Datos del pago</div>
    <div class="card-body">
        <form method="POST" action="{{ route('pagos.store') }}">
            @csrf
            @include('pagos._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-gold">
                    <i class="bi bi-check-circle me-1"></i>Confirmar Pago
                </button>
                <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Si viene cuota_id por URL, auto-seleccionarla
    const urlParams = new URLSearchParams(window.location.search);
    const cuotaId = urlParams.get('cuota_id');
    if (cuotaId) {
        const select = document.getElementById('cuota_id');
        if (select) {
            select.value = cuotaId;
            select.dispatchEvent(new Event('change'));
        }
    }
</script>
@endpush