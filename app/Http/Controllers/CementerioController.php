<?php

namespace App\Http\Controllers;

use App\Models\Cementerio;
use App\Http\Requests\CementerioRequest;
use Illuminate\Http\Request;

class CementerioController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('cementerios.ver');
        $query = Cementerio::withCount('espacios');

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where('nombre', 'like', "%$b%")
                ->orWhere('localizacion', 'like', "%$b%");
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $cementerios = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('cementerios.index', compact('cementerios'));
    }

    public function create()
    {
        #$this->authorize('cementerios.crear');
        $this->authorize('cementerios.ver');
        return view('cementerios.create');
    }

    public function store(CementerioRequest $request)
    {
        #$this->authorize('cementerios.crear');
        $this->authorize('cementerios.ver');
        Cementerio::create($request->validated());
        return redirect()->route('cementerios.index')->with('success', 'Cementerio registrado correctamente.');
    }

    public function show(Cementerio $cementerio)
    {
        #$this->authorize('cementerios.ver');
        $this->authorize('cementerios.ver');
        $cementerio->load(['espacios.tipoInhumacion', 'espacios.direccion']);
        return view('cementerios.show', compact('cementerio'));
    }

    public function edit(Cementerio $cementerio)
    {
        #$this->authorize('cementerios.editar');
        $this->authorize('cementerios.ver');
        return view('cementerios.edit', compact('cementerio'));
    }

    public function update(CementerioRequest $request, Cementerio $cementerio)
    {
        #$this->authorize('cementerios.editar');
        $this->authorize('cementerios.ver');
        $cementerio->update($request->validated());
        return redirect()->route('cementerios.index')->with('success', 'Cementerio actualizado.');
    }

    public function destroy(Cementerio $cementerio)
    {
        #$this->authorize('cementerios.eliminar');
        $this->authorize('cementerios.ver');
        if ($cementerio->espacios()->count() > 0) {
            return back()->with('error', 'No se puede eliminar: el cementerio tiene espacios registrados.');
        }
        $cementerio->delete();
        return redirect()->route('cementerios.index')->with('success', 'Cementerio eliminado.');
    }
}
