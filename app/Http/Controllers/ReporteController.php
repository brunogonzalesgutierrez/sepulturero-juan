<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Pago;
use App\Models\Contrato;
use App\Models\Espacio;
use App\Models\Cliente;
use App\Models\Cuota;
use App\Models\Mantenimiento;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index()
    {
        $this->authorize('reportes.ver');
        return view('reportes.index');
    }

    // ── REPORTE 1: Ventas por período ──────────────────────────────
    public function ventas(Request $request)
    {
        $this->authorize('reportes.ver');

        $desde  = $request->get('desde', now()->startOfMonth()->format('Y-m-d'));
        $hasta  = $request->get('hasta', now()->format('Y-m-d'));
        $tipo   = $request->get('tipo_venta', '');
        $moneda = $request->get('moneda', '');

        $query = Venta::with(['cliente', 'empleado', 'contrato.espacio.cementerio'])
            ->whereBetween('fecha_venta', [$desde, $hasta]);

        if ($tipo)   $query->where('tipo_venta', $tipo);
        if ($moneda) $query->where('moneda', $moneda);

        $ventas = $query->orderBy('fecha_venta', 'desc')->get();

        // Datos para gráfica
        $ventasPorDia = $ventas->groupBy(fn($v) => $v->fecha_venta->format('d/m'))
            ->map(fn($g) => $g->sum('precio_total'));

        $totalContado = $ventas->where('tipo_venta', 'contado')->sum('precio_total');
        $totalCredito = $ventas->where('tipo_venta', 'credito')->sum('precio_total');

        if ($request->get('exportar') === 'pdf') {
            $pdf = Pdf::loadView('reportes.pdf.ventas', compact('ventas', 'desde', 'hasta', 'totalContado', 'totalCredito'))
                ->setPaper('a4', 'landscape');
            return $pdf->download("reporte_ventas_{$desde}_{$hasta}.pdf");
        }

        return view('reportes.ventas', compact(
            'ventas',
            'desde',
            'hasta',
            'tipo',
            'moneda',
            'ventasPorDia',
            'totalContado',
            'totalCredito'
        ));
    }

    // ── REPORTE 2: Pagos / Cobranzas ──────────────────────────────
    public function pagos(Request $request)
    {
        $this->authorize('reportes.ver');

        $desde  = $request->get('desde', now()->startOfMonth()->format('Y-m-d'));
        $hasta  = $request->get('hasta', now()->format('Y-m-d'));
        $metodo = $request->get('metodo_pago', '');

        $query = Pago::with([
            'empleado',
            'cuota.planPago.pagoCredito.venta.cliente',
        ])
            ->whereBetween('fecha_pago', [$desde, $hasta]);

        if ($metodo) $query->where('metodo_pago', $metodo);

        $pagos       = $query->orderBy('fecha_pago', 'desc')->get();
        $totalCobrado = $pagos->sum('monto_pagado');

        $pagosPorMetodo = $pagos->groupBy('metodo_pago')
            ->map(fn($g) => $g->sum('monto_pagado'));

        $cuotasVencidas = Cuota::where('estado', 'vencida')
            ->with(['planPago.pagoCredito.venta.cliente'])
            ->orderBy('fecha_vencimiento')
            ->get();

        if ($request->get('exportar') === 'pdf') {
            $pdf = Pdf::loadView('reportes.pdf.pagos', compact('pagos', 'desde', 'hasta', 'totalCobrado', 'cuotasVencidas'))
                ->setPaper('a4', 'landscape');
            return $pdf->download("reporte_pagos_{$desde}_{$hasta}.pdf");
        }

        return view('reportes.pagos', compact(
            'pagos',
            'desde',
            'hasta',
            'metodo',
            'totalCobrado',
            'pagosPorMetodo',
            'cuotasVencidas'
        ));
    }

    // ── REPORTE 3: Estado de espacios ─────────────────────────────
    public function espacios(Request $request)
    {
        $this->authorize('reportes.ver');

        $cementerioId = $request->get('cementerio_id', '');

        $query = Espacio::with(['cementerio', 'tipoInhumacion', 'direccion', 'contratos.cliente']);
        if ($cementerioId) $query->where('cementerio_id', $cementerioId);

        $espacios = $query->orderBy('cementerio_id')->get();

        $porEstado = $espacios->groupBy('estado')
            ->map(fn($g) => $g->count());

        $porTipo = $espacios->groupBy('tipoInhumacion.nombre')
            ->map(fn($g) => $g->count());

        $cementerios = \App\Models\Cementerio::where('estado', 'activo')->orderBy('nombre')->get();

        if ($request->get('exportar') === 'pdf') {
            $pdf = Pdf::loadView('reportes.pdf.espacios', compact('espacios', 'porEstado', 'porTipo'))
                ->setPaper('a4', 'landscape');
            return $pdf->download("reporte_espacios.pdf");
        }

        return view('reportes.espacios', compact(
            'espacios',
            'porEstado',
            'porTipo',
            'cementerios',
            'cementerioId'
        ));
    }

    // ── REPORTE 4: Contratos activos con saldo pendiente ──────────
    public function contratos(Request $request)
    {
        $this->authorize('reportes.ver');

        $estado = $request->get('estado', 'activo');
        $moneda = $request->get('moneda', '');

        $query = Contrato::with(['cliente', 'espacio.cementerio', 'venta'])
            ->where('estado', $estado);

        if ($moneda) $query->where('moneda', $moneda);

        $contratos     = $query->orderBy('created_at', 'desc')->get();
        $totalSaldo    = $contratos->sum('saldo_pendiente');
        $totalMonto    = $contratos->sum('monto_base');

        if ($request->get('exportar') === 'pdf') {
            $pdf = Pdf::loadView('reportes.pdf.contratos', compact('contratos', 'estado', 'totalSaldo', 'totalMonto'))
                ->setPaper('a4', 'landscape');
            return $pdf->download("reporte_contratos_{$estado}.pdf");
        }

        return view('reportes.contratos', compact(
            'contratos',
            'estado',
            'moneda',
            'totalSaldo',
            'totalMonto'
        ));
    }
}
