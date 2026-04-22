<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\Espacio;
use App\Http\Requests\ContratoRequest;
use Illuminate\Http\Request;
use App\Services\BitacoraService;

class ContratoController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('contratos.ver');

        $query = Contrato::with(['cliente', 'espacio.cementerio', 'espacio.direccion', 'venta']);

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->whereHas(
                'cliente',
                fn($q) =>
                $q->where('ci', 'like', "%$b%")
                    ->orWhere('nombre', 'like', "%$b%")
                    ->orWhere('paterno', 'like', "%$b%")
            );
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('moneda')) {
            $query->where('moneda', $request->moneda);
        }

        $contratos = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('contratos.index', compact('contratos'));
    }

    public function create()
    {
        #$this->authorize('contratos.crear');
        $this->authorize('contratos.ver');

        $clientes = Cliente::where('estado', 'activo')
            ->orderBy('paterno')
            ->get();

        $espacios = Espacio::where('estado', 'disponible')
            ->with(['cementerio', 'direccion', 'tipoInhumacion', 'dimension'])
            ->get();

        $contrato = null; // 👈 CLAVE

        return view('contratos.create', compact('clientes', 'espacios', 'contrato'));
    }

    public function store(ContratoRequest $request)
    {
        #$this->authorize('contratos.crear');
        $this->authorize('contratos.ver');

        $data = $request->validated();
        $data['saldo_pendiente'] = $data['monto_base'];

        $contrato = Contrato::create($data);

        // Reservar el espacio
        $contrato->espacio->update(['estado' => 'reservado']);

        BitacoraService::registrar('contratos', $contrato->id, "Contrato creado para cliente #{$contrato->cliente_id}, espacio #{$contrato->espacio_id}");

        return redirect()->route('contratos.show', $contrato)
            ->with('success', 'Contrato registrado. Ahora registra la venta.');
    }

    public function show(Contrato $contrato)
    {
        #$this->authorize('contratos.ver');
        $this->authorize('contratos.ver');
        $contrato->load([
            'cliente',
            'espacio.cementerio',
            'espacio.direccion',
            'espacio.tipoInhumacion',
            'espacio.dimension',
            'venta.pagoContado',
            'venta.pagoCredito.planPago.cuotas',
            'inhumaciones',
        ]);
        return view('contratos.show', compact('contrato'));
    }

    public function edit(Contrato $contrato)
    {
        #$this->authorize('contratos.editar');
        $this->authorize('contratos.ver');
        $clientes = Cliente::where('estado', 'activo')->orderBy('paterno')->get();
        $espacios = Espacio::whereIn('estado', ['disponible', 'reservado'])
            ->with(['cementerio', 'direccion', 'tipoInhumacion'])
            ->get();
        return view('contratos.edit', compact('contrato', 'clientes', 'espacios'));
    }

    public function update(ContratoRequest $request, Contrato $contrato)
    {
        #$this->authorize('contratos.editar');
        $this->authorize('contratos.ver');
        $contrato->update($request->validated());
        BitacoraService::registrar('contratos', $contrato->id, "Contrato #{$contrato->id} actualizado");
        return redirect()->route('contratos.show', $contrato)
            ->with('success', 'Contrato actualizado.');
    }

    public function destroy(Contrato $contrato)
    {
        #$this->authorize('contratos.editar');
        $this->authorize('contratos.ver');
        if ($contrato->venta) {
            return back()->with('error', 'No se puede eliminar: el contrato tiene una venta asociada.');
        }
        // Liberar espacio
        $contrato->espacio->update(['estado' => 'disponible']);
        $contrato->delete();
        return redirect()->route('contratos.index')->with('success', 'Contrato eliminado.');
    }
}
