<?php

namespace App\Http\Controllers;

use App\Models\Inhumacion;
use App\Models\Espacio;
use App\Models\Contrato;
use App\Http\Requests\InhumacionRequest;
use Illuminate\Http\Request;

class InhumacionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('inhumaciones.ver');

        $query = Inhumacion::with(['espacio.cementerio', 'espacio.direccion', 'contrato.cliente']);

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function ($q) use ($b) {
                $q->where('nombre', 'like', "%$b%")
                    ->orWhere('paterno', 'like', "%$b%")
                    ->orWhere('materno', 'like', "%$b%")
                    ->orWhereHas(
                        'contrato.cliente',
                        fn($c) =>
                        $c->where('nombre', 'like', "%$b%")
                            ->orWhere('paterno', 'like', "%$b%")
                            ->orWhere('ci', 'like', "%$b%")
                    );
            });
        }

        if ($request->filled('cementerio_id')) {
            $query->whereHas('espacio', fn($q) => $q->where('cementerio_id', $request->cementerio_id));
        }

        $inhumaciones = $query->orderBy('fecha_inhumacion', 'desc')->paginate(15)->withQueryString();
        $cementerios  = \App\Models\Cementerio::where('estado', 'activo')->orderBy('nombre')->get();

        return view('inhumaciones.index', compact('inhumaciones', 'cementerios'));
    }

    public function create()
    {
        $this->authorize('inhumaciones.crear');

        // Solo espacios con contrato activo
        $espacios = Espacio::whereIn('estado', ['disponible', 'ocupado', 'reservado'])
            ->with(['cementerio', 'direccion', 'tipoInhumacion'])
            ->get();
        $contratos = Contrato::where('estado', 'activo')
            ->with('cliente')
            ->orderBy('created_at', 'desc')
            ->get();

        $inhumacion = new Inhumacion();

        return view('inhumaciones.create', compact('espacios', 'contratos', 'inhumacion'));
    }

    public function store(InhumacionRequest $request)
    {
        $this->authorize('inhumaciones.crear');

        $inhumacion = Inhumacion::create($request->validated());

        // Marcar espacio como ocupado
        $inhumacion->espacio->update(['estado' => 'ocupado']);

        return redirect()->route('inhumaciones.index')
            ->with('success', 'Inhumación registrada correctamente.');
    }

    public function show(Inhumacion $inhumacion)
    {
        $this->authorize('inhumaciones.ver');
        $inhumacion->load(['espacio.cementerio', 'espacio.direccion', 'espacio.tipoInhumacion', 'contrato.cliente']);
        return view('inhumaciones.show', compact('inhumacion'));
    }

    public function edit(Inhumacion $inhumacion)
    {
        $this->authorize('inhumaciones.editar');
        $espacios = Espacio::whereIn('estado', ['disponible', 'ocupado', 'reservado'])
            ->with(['cementerio', 'direccion', 'tipoInhumacion'])
            ->get();
        $contratos = Contrato::where('estado', 'activo')
            ->with('cliente')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('inhumaciones.edit', compact('inhumacion', 'espacios', 'contratos'));
    }

    public function update(InhumacionRequest $request, Inhumacion $inhumacion)
    {
        $this->authorize('inhumaciones.editar');
        $inhumacion->update($request->validated());
        return redirect()->route('inhumaciones.index')
            ->with('success', 'Inhumación actualizada correctamente.');
    }

    public function destroy(Inhumacion $inhumacion)
    {
        $this->authorize('inhumaciones.eliminar');
        $espacio = $inhumacion->espacio;
        $inhumacion->delete();

        // Si no quedan más inhumaciones, liberar espacio
        if ($espacio->inhumaciones()->count() === 0) {
            $espacio->update(['estado' => 'disponible']);
        }

        return redirect()->route('inhumaciones.index')
            ->with('success', 'Inhumación eliminada.');
    }
}
