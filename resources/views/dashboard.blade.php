@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </h1>
    <span class="text-muted" style="font-size:0.85rem;">
        {{ now()->translatedFormat('l, j \d\e F \d\e Y') }}
    </span>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-number">{{ $stats['clientes'] ?? 0 }}</div>
                    <div class="stat-label">Clientes</div>
                </div>
                <i class="bi bi-people stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-number">{{ $stats['espacios_disponibles'] ?? 0 }}</div>
                    <div class="stat-label">Espacios Disponibles</div>
                </div>
                <i class="bi bi-grid-3x3-gap stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-number">{{ $stats['ventas_mes'] ?? 0 }}</div>
                    <div class="stat-label">Ventas este mes</div>
                </div>
                <i class="bi bi-cart-check stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-number">{{ $stats['cuotas_vencidas'] ?? 0 }}</div>
                    <div class="stat-label">Cuotas Vencidas</div>
                </div>
                <i class="bi bi-exclamation-triangle stat-icon" style="color:#dc3545;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header py-2">
                <i class="bi bi-bar-chart me-2"></i>Ventas últimos 6 meses
            </div>
            <div class="card-body">
                <canvas id="ventasChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header py-2">
                <i class="bi bi-pie-chart me-2"></i>Estado de Espacios
            </div>
            <div class="card-body">
                <canvas id="espaciosChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
$meses = $stats['meses'] ?? [];
$ventas = $stats['ventas_por_mes'] ?? [];
$espaciosEstado = $stats['espacios_estado'] ?? [0, 0, 0, 0];
@endphp

<script>
    // Ventas chart
    new Chart(document.getElementById('ventasChart'), {
        type: 'bar',
        data: {
            labels: {
                !!json_encode($meses) !!
            },
            datasets: [{
                label: 'Ventas (BOB)',
                data: {
                    !!json_encode($ventas) !!
                },
                backgroundColor: 'rgba(201,168,76,0.7)',
                borderColor: '#c9a84c',
                borderWidth: 2,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Espacios chart
    new Chart(document.getElementById('espaciosChart'), {
        type: 'doughnut',
        data: {
            labels: ['Disponible', 'Ocupado', 'Mantenimiento', 'Reservado'],
            datasets: [{
                data: {
                    !!json_encode($espaciosEstado) !!
                },
                backgroundColor: ['#198754', '#dc3545', '#ffc107', '#0dcaf0'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush