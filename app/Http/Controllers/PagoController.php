<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Cuota;
use App\Models\Empleado;
use App\Models\PlanPago;
use App\Models\Contrato;
use App\Http\Requests\PagoRequest;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('pagos.ver');

        // Si viene filtro de venta, mostrar cuotas de esa venta
        $ventaId = $request->venta_id;

        $query = Pago::with([
            'cuota.planPago.pagoCredito.venta.cliente',
            'empleado',
        ]);

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->whereHas(
                'cuota.planPago.pagoCredito.venta.cliente',
                fn($q) =>
                $q->where('nombre', 'like', "%$b%")
                    ->orWhere('paterno', 'like', "%$b%")
                    ->orWhere('ci', 'like', "%$b%")
            );
        }

        if ($ventaId) {
            $query->whereHas(
                'cuota.planPago.pagoCredito',
                fn($q) =>
                $q->where('venta_id', $ventaId)
            );
        }

        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        $pagos = $query->orderBy('fecha_pago', 'desc')->paginate(15)->withQueryString();

        // Cuotas pendientes/vencidas para el panel de registro rápido
        $cuotasPendientes = Cuota::whereIn('estado', ['pendiente', 'vencida'])
            ->with(['planPago.pagoCredito.venta.cliente'])
            ->orderBy('fecha_vencimiento')
            ->get();

        $empleados = Empleado::where('estado', 'activo')->orderBy('nombre')->get();

        return view('pagos.index', compact('pagos', 'cuotasPendientes', 'empleados', 'ventaId'));
    }

    public function create()
    {
        $this->authorize('pagos.crear');

        $cuotasPendientes = Cuota::whereIn('estado', ['pendiente', 'vencida'])
            ->with(['planPago.pagoCredito.venta.cliente'])
            ->orderBy('fecha_vencimiento')
            ->get();

        $empleados = Empleado::where('estado', 'activo')->orderBy('nombre')->get();

        return view('pagos.create', compact('cuotasPendientes', 'empleados'));
    }

    public function store(PagoRequest $request)
    {
        $this->authorize('pagos.crear');

        DB::transaction(function () use ($request) {
            $cuota = Cuota::findOrFail($request->cuota_id);

            // 1. Registrar el pago
            $pago = Pago::create([
                'cuota_id'      => $request->cuota_id,
                'empleado_id'   => $request->empleado_id,
                'fecha_pago'    => $request->fecha_pago,
                'monto_pagado'  => $request->monto_pagado,
                'monto_interes' => $request->monto_interes ?? 0,
                'metodo_pago'   => $request->metodo_pago,
                'comprobante'   => $request->comprobante,
            ]);

            // 2. Calcular total pagado en esta cuota
            $totalPagado = $cuota->pagos()->sum('monto_pagado');

            // 3. Actualizar estado de la cuota
            if ($totalPagado >= $cuota->monto) {
                $cuota->update(['estado' => 'pagada']);
            }

            // 4. Actualizar saldo pendiente del contrato
            $planPago = $cuota->planPago;
            $contrato = $planPago->pagoCredito->venta->contrato;
            $nuevoSaldo = max(0, $contrato->saldo_pendiente - $request->monto_pagado);
            $contrato->update(['saldo_pendiente' => $nuevoSaldo]);

            // 5. Si todas las cuotas están pagadas → marcar contrato como pagado
            $cuotasPendientes = $planPago->cuotas()
                ->whereIn('estado', ['pendiente', 'vencida'])
                ->count();

            if ($cuotasPendientes === 0) {
                $contrato->update(['estado' => 'pagado', 'saldo_pendiente' => 0]);
            }

            // 6. Bitácora
            BitacoraService::registrar(
                'pagos',
                $pago->id,
                "Pago de {$request->monto_pagado} registrado para cuota #{$cuota->nro_cuota} (Contrato #{$contrato->id})"
            );
        });

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function show(Pago $pago)
    {
        $this->authorize('pagos.ver');
        $pago->load([
            'empleado',
            'cuota.planPago.pagoCredito.venta.cliente',
            'cuota.planPago.pagoCredito.venta.contrato.espacio.cementerio',
        ]);
        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago)
    {
        $this->authorize('pagos.editar');
        $empleados = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        $pago->load(['cuota.planPago.pagoCredito.venta.cliente']);
        return view('pagos.edit', compact('pago', 'empleados'));
    }

    public function update(PagoRequest $request, Pago $pago)
    {
        $this->authorize('pagos.editar');

        DB::transaction(function () use ($request, $pago) {
            $montoAnterior = $pago->monto_pagado;
            $pago->update($request->validated());

            // Recalcular estado de cuota
            $cuota       = $pago->cuota;
            $totalPagado = $cuota->pagos()->sum('monto_pagado');
            $nuevoEstado = $totalPagado >= $cuota->monto ? 'pagada' : 'pendiente';
            $cuota->update(['estado' => $nuevoEstado]);

            // Recalcular saldo del contrato
            $contrato = $cuota->planPago->pagoCredito->venta->contrato;
            $diferencia = $request->monto_pagado - $montoAnterior;
            $nuevoSaldo = max(0, $contrato->saldo_pendiente - $diferencia);
            $contrato->update(['saldo_pendiente' => $nuevoSaldo]);

            BitacoraService::registrar(
                'pagos',
                $pago->id,
                "Pago #{$pago->id} actualizado. Monto anterior: {$montoAnterior}, nuevo: {$request->monto_pagado}"
            );
        });

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado.');
    }

    public function destroy(Pago $pago)
    {
        return back()->with('error', 'Los pagos no pueden eliminarse por integridad del sistema.');
    }

    // Marcar cuotas vencidas (llamar desde scheduler o manualmente)
    public function marcarVencidas()
    {
        $this->authorize('pagos.editar');
        $cantidad = Cuota::where('estado', 'pendiente')
            ->where('fecha_vencimiento', '<', now()->toDateString())
            ->update(['estado' => 'vencida']);

        return back()->with('success', "{$cantidad} cuotas marcadas como vencidas.");
    }

    public function enviarRecibo(Pago $pago)
    {
        $this->authorize('pagos.ver');

        $pago->load([
            'empleado',
            'cuota.planPago.pagoCredito.venta.cliente',
            'cuota.planPago.pagoCredito.venta.contrato'
        ]);
        $correo = $pago->cuota->planPago->pagoCredito->venta->cliente->correo;

        if (!$correo) {
            return back()->with('error', 'El cliente no tiene correo registrado.');
        }

        try {
            Mail::to($correo)->send(new ReciboPagoMail($pago));
            return back()->with('success', "Recibo enviado a {$correo}");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar: ' . $e->getMessage());
        }
    }
}
