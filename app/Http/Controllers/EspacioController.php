<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use App\Models\Cementerio;
use App\Models\Dimension;
use App\Models\TipoInhumacion;
use App\Models\Direccion;
use App\Http\Requests\EspacioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EspacioController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('espacios.ver');

        $query = Espacio::with(['cementerio', 'tipoInhumacion', 'dimension', 'direccion']);

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->whereHas('cementerio', fn($q) => $q->where('nombre', 'like', "%$b%"))
                ->orWhereHas(
                    'direccion',
                    fn($q) =>
                    $q->where('seccion', 'like', "%$b%")
                        ->orWhere('numero', 'like', "%$b%")
                        ->orWhere('fila', 'like', "%$b%")
                );
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('cementerio_id')) {
            $query->where('cementerio_id', $request->cementerio_id);
        }
        if ($request->filled('tipo_inhumacion_id')) {
            $query->where('tipo_inhumacion_id', $request->tipo_inhumacion_id);
        }

        $espacios      = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $cementerios   = Cementerio::where('estado', 'activo')->orderBy('nombre')->get();
        $tipos         = TipoInhumacion::where('estado', 'activo')->orderBy('nombre')->get();

        return view('espacios.index', compact('espacios', 'cementerios', 'tipos'));
    }

    public function create()
    {
        $this->authorize('espacios.crear');
        $cementerios = Cementerio::where('estado', 'activo')->orderBy('nombre')->get();
        $tipos       = TipoInhumacion::where('estado', 'activo')->orderBy('nombre')->get();
        return view('espacios.create', compact('cementerios', 'tipos'));
    }

    public function store(EspacioRequest $request)
    {
        $this->authorize('espacios.crear');
        DB::transaction(function () use ($request) {
            // 1. Crear dimensión
            $dimension = Dimension::create([
                'ancho' => $request->ancho,
                'largo' => $request->largo,
            ]);

            // 2. Crear espacio
            $espacio = Espacio::create([
                'cementerio_id'      => $request->cementerio_id,
                'dimension_id'       => $dimension->id,
                'tipo_inhumacion_id' => $request->tipo_inhumacion_id,
                'estado'             => $request->estado,
                'precio_m2'          => $request->precio_m2,
            ]);

            // 3. Crear dirección
            Direccion::create([
                'espacio_id' => $espacio->id,
                'seccion'    => $request->seccion,
                'numero'     => $request->numero,
                'calle'      => $request->calle,
                'fila'       => $request->fila,
            ]);
        });

        return redirect()->route('espacios.index')->with('success', 'Espacio registrado correctamente.');
    }

    public function show(Espacio $espacio)
    {
        $this->authorize('espacios.ver');
        $espacio->load(['cementerio', 'tipoInhumacion', 'dimension', 'direccion', 'inhumaciones', 'mantenimientos']);
        return view('espacios.show', compact('espacio'));
    }

    public function edit(Espacio $espacio)
    {
        $this->authorize('espacios.editar');
        $cementerios = Cementerio::where('estado', 'activo')->orderBy('nombre')->get();
        $tipos       = TipoInhumacion::where('estado', 'activo')->orderBy('nombre')->get();
        $espacio->load(['dimension', 'direccion']);

        return view('espacios.edit', compact('espacio', 'cementerios', 'tipos'));
    }

    public function update(EspacioRequest $request, Espacio $espacio)
    {
        $this->authorize('espacios.editar');

        DB::transaction(function () use ($request, $espacio) {
            // Actualizar dimensión
            $espacio->dimension->update([
                'ancho' => $request->ancho,
                'largo' => $request->largo,
            ]);

            // Actualizar espacio
            $espacio->update([
                'cementerio_id'      => $request->cementerio_id,
                'tipo_inhumacion_id' => $request->tipo_inhumacion_id,
                'estado'             => $request->estado,
                'precio_m2'          => $request->precio_m2,
            ]);

            // Actualizar dirección
            if ($espacio->direccion == null) {
                // 3. Crear dirección
                $direccion = Direccion::create([
                    'espacio_id' => $espacio->id,
                    'seccion'    => $request->seccion,
                    'numero'     => $request->numero,
                    'calle'      => $request->calle,
                    'fila'       => $request->fila,
                ]);
                $espacio->direccion = $direccion;
            } else {
                $espacio->direccion->update([
                    'seccion' => $request->seccion,
                    'numero'  => $request->numero,
                    'calle'   => $request->calle,
                    'fila'    => $request->fila,
                ]);
            }
        });

        return redirect()->route('espacios.index')->with('success', 'Espacio actualizado correctamente.');
    }

    public function destroy(Espacio $espacio)
    {
        $this->authorize('espacios.eliminar');

        if ($espacio->inhumaciones()->count() > 0 || $espacio->contratos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar: el espacio tiene inhumaciones o contratos asociados.');
        }

        DB::transaction(function () use ($espacio) {
            $dimension = $espacio->dimension;

            $espacio->direccion?->delete();
            $espacio->forceDelete(); // elimina físicamente aunque tenga SoftDeletes
            $dimension?->delete();
        });

        return redirect()->route('espacios.index')->with('success', 'Espacio eliminado.');
    }
}
