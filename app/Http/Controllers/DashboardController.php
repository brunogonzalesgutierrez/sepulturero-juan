<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Espacio;
use App\Models\Venta;
use App\Models\Cuota;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'clientes'           => Cliente::count(),
            'espacios_disponibles' => Espacio::where('estado', 'disponible')->count(),
            'ventas_mes'         => Venta::whereMonth('fecha_venta', now()->month)->count(),
            'cuotas_vencidas'    => Cuota::where('estado', 'vencida')->count(),
            'meses'              => collect(range(5, 0))->map(fn($i) => now()->subMonths($i)->isoFormat('MMM'))->values(),
            'ventas_por_mes'     => collect(range(5, 0))->map(
                fn($i) =>
                Venta::whereMonth('fecha_venta', now()->subMonths($i)->month)
                    ->whereYear('fecha_venta', now()->subMonths($i)->year)
                    ->sum('precio_total')
            )->values(),
            'espacios_estado'    => [
                Espacio::where('estado', 'disponible')->count(),
                Espacio::where('estado', 'ocupado')->count(),
                Espacio::where('estado', 'mantenimiento')->count(),
                Espacio::where('estado', 'reservado')->count(),
            ],
        ];

        return view('dashboard', compact('stats'));
    }
}
