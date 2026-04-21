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
            'nombre' => 'required|string|max:255|unique:tipo_inhumaciones,nombre',
            'precio' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'precio_base' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'capacidad_max' => 'required|integer|min:1',
            'estado' => 'required|in:activo,inactivo,mantenimiento',
            'area_base' => 'required|numeric|min:0.01|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        TipoInhumacion::create($data);

        return redirect()
            ->route('tipo_inhumaciones.index')
            ->with('success', 'Tipo registrado correctamente.');
    }

    public function edit($id)
    {
        $tipoInhumacion = TipoInhumacion::findorfail($id);

        $this->authorize('cementerios.editar');

        return view('tipo_inhumaciones.edit', compact('tipoInhumacion'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('cementerios.editar');
        $tipoInhumacion = TipoInhumacion::findorfail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_inhumaciones,nombre,' . $tipoInhumacion->id,
            'precio' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'precio_base' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'capacidad_max' => 'required|integer|min:1',
            'estado' => 'required|in:activo,inactivo,mantenimiento',
            'area_base' => 'required|numeric|min:0.01|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        $tipoInhumacion->update($data);

        return redirect()
            ->route('tipo_inhumaciones.index')
            ->with('success', 'Tipo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $this->authorize('cementerios.eliminar');
        $tipoInhumacion = TipoInhumacion::findorfail($id);
        if ($tipoInhumacion->espacios()->count() > 0) {
            return back()->with('error', 'No se puede eliminar: tiene espacios asociados.');
        }

        $tipoInhumacion->delete();

        return redirect()
            ->route('tipo_inhumaciones.index')
            ->with('success', 'Tipo eliminado.');
    }
}
