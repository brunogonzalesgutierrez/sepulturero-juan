<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Contrato;
use App\Models\Empleado;
use App\Models\DetalleVenta;
use App\Models\PagoContado;
use App\Models\PagoCredito;
use App\Models\PlanPago;
use App\Models\Cuota;
use App\Http\Requests\VentaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\FacturaVentaMail;
use Illuminate\Support\Facades\Mail;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('ventas.ver');

        $query = Venta::with(['cliente', 'empleado', 'contrato.espacio.cementerio']);

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->whereHas(
                'cliente',
                fn($q) =>
                $q->where('nombre', 'like', "%$b%")
                    ->orWhere('paterno', 'like', "%$b%")
                    ->orWhere('ci', 'like', "%$b%")
            );
        }
        if ($request->filled('tipo_venta')) {
            $query->where('tipo_venta', $request->tipo_venta);
        }
        if ($request->filled('moneda')) {
            $query->where('moneda', $request->moneda);
        }

        $ventas = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $this->authorize('ventas.crear');
        // Solo contratos activos sin venta aún
        $contratos = Contrato::where('estado', 'activo')
            ->whereDoesntHave('venta')
            ->with(['cliente', 'espacio.cementerio', 'espacio.direccion'])
            ->get();
        $empleados = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        return view('ventas.create', compact('contratos', 'empleados'));
    }

    public function store(VentaRequest $request)
    {
        $this->authorize('ventas.crear');

        DB::transaction(function () use ($request) {

            // 1. Crear la venta
            $venta = Venta::create([
                'contrato_id'  => $request->contrato_id,
                'cliente_id'   => $request->cliente_id,
                'empleado_id'  => $request->empleado_id,
                'fecha_venta'  => $request->fecha_venta,
                'precio_total' => $request->precio_total,
                'tipo_venta'   => $request->tipo_venta,
                'moneda'       => $request->moneda,
            ]);

            $contrato = Contrato::with('cliente')->findOrFail($request->contrato_id);

            // 2. Detalle
            DetalleVenta::create([
                'venta_id'        => $venta->id,
                'espacio_id'      => $contrato->espacio_id,
                'precio_unitario' => $request->precio_total,
            ]);

            // 3. Marcar espacio ocupado
            $contrato->espacio->update(['estado' => 'ocupado']);

            if ($request->tipo_venta === 'contado') {

                PagoContado::create([
                    'venta_id'    => $venta->id,
                    'descuento'   => $request->descuento ?? 0,
                    'metodo_pago' => $request->metodo_pago,
                ]);

                $contrato->update([
                    'estado' => 'pagado',
                    'saldo_pendiente' => 0
                ]);
            } else {

                $montoConInteres = $request->precio_total * (1 + ($request->interes / 100));

                $pagoCredito = PagoCredito::create([
                    'venta_id'      => $venta->id,
                    'interes'       => $request->interes,
                    'monto_inicial' => $montoConInteres,
                ]);

                $planPago = PlanPago::create([
                    'pago_credito_id' => $pagoCredito->id,
                    'fecha_inicio'    => $request->fecha_inicio,
                    'fecha_fin'       => $request->fecha_fin,
                    'frecuencia'      => $request->frecuencia,
                    'monto'           => round($montoConInteres / $request->nro_cuotas, 2),
                    'interes_anual'   => $request->interes,
                ]);

                $this->generarCuotas(
                    $planPago,
                    $request->nro_cuotas,
                    $request->frecuencia,
                    $request->fecha_inicio
                );

                $contrato->update([
                    'saldo_pendiente' => $montoConInteres
                ]);
            }

            // ✅ ENVÍO DE CORREO DESPUÉS DEL COMMIT (FORMA PRO)
            DB::afterCommit(function () use ($venta, $contrato) {

                $clienteCorreo = optional($contrato->cliente)->correo;

                if ($clienteCorreo) {
                    try {
                        Mail::to($clienteCorreo)->send(
                            new FacturaVentaMail(
                                $venta->load([
                                    'cliente',
                                    'empleado',
                                    'contrato.espacio.cementerio',
                                    'contrato.espacio.direccion',
                                    'contrato.espacio.tipoInhumacion',
                                    'contrato.espacio.dimension',
                                    'pagoContado',
                                    'pagoCredito.planPago.cuotas',
                                ])
                            )
                        );
                    } catch (\Exception $e) {
                        \Log::error("Error enviando factura venta #{$venta->id}: " . $e->getMessage());
                    }
                }
            });
        });

        return redirect()->route('ventas.index')
            ->with('success', 'Venta registrada correctamente.');
    }

    private function generarCuotas(PlanPago $planPago, int $nroCuotas, string $frecuencia, string $fechaInicio): void
    {
        $fecha = Carbon::parse($fechaInicio);
        $diasIncremento = match ($frecuencia) {
            'semanal'   => 7,
            'quincenal' => 15,
            'mensual'   => 30,
        };

        for ($i = 1; $i <= $nroCuotas; $i++) {
            Cuota::create([
                'plan_pago_id'      => $planPago->id,
                'nro_cuota'         => $i,
                'estado'            => 'pendiente',
                'fecha_vencimiento' => $fecha->copy()->addDays($diasIncremento * $i),
                'monto'             => $planPago->monto,
            ]);
        }
    }

    public function show(Venta $venta)
    {
        $this->authorize('ventas.ver');
        $venta->load([
            'cliente',
            'empleado',
            'contrato.espacio.cementerio',
            'contrato.espacio.direccion',
            'detalles.espacio',
            'pagoContado',
            'pagoCredito.planPago.cuotas.pagos',
        ]);
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        // Solo permitir editar ventas sin pagos registrados
        $this->authorize('ventas.editar');
        return back()->with('error', 'Las ventas no pueden editarse directamente. Gestione desde el contrato.');
    }

    public function update(Request $request, Venta $venta)
    {
        return back()->with('error', 'Operación no permitida.');
    }

    public function destroy(Venta $venta)
    {
        $this->authorize('ventas.eliminar');
        return back()->with('error', 'Las ventas no pueden eliminarse por integridad del sistema.');
    }
}
