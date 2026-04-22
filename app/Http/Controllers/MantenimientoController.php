<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use App\Models\Espacio;
use App\Http\Requests\MantenimientoRequest;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('mantenimientos.ver');

        $query = Mantenimiento::with(['espacio.cementerio', 'espacio.direccion']);

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where('descripcion', 'like', "%$b%")
                ->orWhereHas('espacio.cementerio', fn($q) => $q->where('nombre', 'like', "%$b%"));
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $mantenimientos = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('mantenimientos.index', compact('mantenimientos'));
    }

    public function create()
    {
        #$this->authorize('mantenimientos.crear');
        $this->authorize('mantenimientos.ver');
        $espacios = Espacio::with(['cementerio', 'direccion'])->orderBy('id')->get();
        $mantenimiento = new Mantenimiento();
        return view('mantenimientos.create', compact('espacios', 'mantenimiento'));
    }

    public function store(MantenimientoRequest $request)
    {
        #$this->authorize('mantenimientos.crear');
        $this->authorize('mantenimientos.ver');

        $mantenimiento = Mantenimiento::create($request->validated());

        // Si el mantenimiento está en proceso u pendiente, marcar espacio
        if (in_array($request->estado, ['pendiente', 'en_proceso'])) {
            $mantenimiento->espacio->update(['estado' => 'mantenimiento']);
        }

        return redirect()->route('mantenimientos.index')
            ->with('success', 'Mantenimiento registrado correctamente.');
    }

    public function show(Mantenimiento $mantenimiento)
    {
        #$this->authorize('mantenimientos.ver');
        $this->authorize('mantenimientos.ver');
        $mantenimiento->load(['espacio.cementerio', 'espacio.direccion', 'espacio.tipoInhumacion']);
        return view('mantenimientos.show', compact('mantenimiento'));
    }

    public function edit(Mantenimiento $mantenimiento)
    {
        #$this->authorize('mantenimientos.editar');
        $this->authorize('mantenimientos.ver');
        $espacios = Espacio::with(['cementerio', 'direccion'])->orderBy('id')->get();
        return view('mantenimientos.edit', compact('mantenimiento', 'espacios'));
    }

    public function update(MantenimientoRequest $request, Mantenimiento $mantenimiento)
    {
        #$this->authorize('mantenimientos.editar');
        $this->authorize('mantenimientos.ver');

        $estadoAnterior = $mantenimiento->estado;
        $mantenimiento->update($request->validated());

        // Si se completó, liberar el espacio si no tiene inhumaciones activas
        if ($request->estado === 'completado' && $estadoAnterior !== 'completado') {
            $espacio = $mantenimiento->espacio;
            $tieneInhumaciones = $espacio->inhumaciones()->count() > 0;
            $espacio->update(['estado' => $tieneInhumaciones ? 'ocupado' : 'disponible']);
        }

        return redirect()->route('mantenimientos.index')
            ->with('success', 'Mantenimiento actualizado correctamente.');
    }

    public function destroy(Mantenimiento $mantenimiento)
    {
        #$this->authorize('mantenimientos.eliminar');
        $this->authorize('mantenimientos.ver');
        $mantenimiento->delete();
        return redirect()->route('mantenimientos.index')
            ->with('success', 'Mantenimiento eliminado.');
    }
}
