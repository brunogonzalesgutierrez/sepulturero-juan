<?php

namespace App\Http\Controllers;

use App\Models\TipoInhumacion;
use Illuminate\Http\Request;

class TipoInhumacionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('cementerios.ver');

        $tipos = TipoInhumacion::orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('tipo_inhumaciones.index', compact('tipos'));
    }

    public function create()
    {
        $this->authorize('cementerios.crear');

        return view('tipo_inhumaciones.create');
    }

    public function store(Request $request)
    {
        $this->authorize('cementerios.crear');

        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_inhumacions,nombre',
            'descripcion' => 'nullable|string',
        ]);

        TipoInhumacion::create($data);

        return redirect()
            ->route('tipo_inhumaciones.index')
            ->with('success', 'Tipo registrado correctamente.');
    }

    public function edit(TipoInhumacion $tipoInhumacion)
    {
        $this->authorize('cementerios.editar');

        return view('tipo_inhumaciones.edit', compact('tipoInhumacion'));
    }

    public function update(Request $request, TipoInhumacion $tipoInhumacion)
    {
        $this->authorize('cementerios.editar');

        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_inhumacions,nombre,' . $tipoInhumacion->id,
            'descripcion' => 'nullable|string',
        ]);

        $tipoInhumacion->update($data);

        return redirect()
            ->route('tipo_inhumaciones.index')
            ->with('success', 'Tipo actualizado.');
    }

    public function destroy(TipoInhumacion $tipoInhumacion)
    {
        $this->authorize('cementerios.eliminar');

        if ($tipoInhumacion->espacios()->count() > 0) {
            return back()->with('error', 'No se puede eliminar: tiene espacios asociados.');
        }

        $tipoInhumacion->delete();

        return redirect()
            ->route('tipo_inhumaciones.index')
            ->with('success', 'Tipo eliminado.');
    }
}
