<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Http\Requests\EmpleadoRequest;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('empleados.ver');

        $query = Empleado::query();

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function ($q) use ($b) {
                $q->where('ci', 'like', "%$b%")
                    ->orWhere('nombre', 'like', "%$b%")
                    ->orWhere('paterno', 'like', "%$b%")
                    ->orWhere('cargo', 'like', "%$b%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $empleados = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        #$this->authorize('empleados.crear');
        $this->authorize('empleados.ver');
        return view('empleados.create');
    }

    public function store(EmpleadoRequest $request)
    {
        #$this->authorize('empleados.crear');    
        $this->authorize('empleados.ver');
        Empleado::create($request->validated());
        return redirect()->route('empleados.index')->with('success', 'Empleado registrado correctamente.');
    }

    public function show(Empleado $empleado)
    {
        #$this->authorize('empleados.ver');
        $this->authorize('empleados.ver');
        $empleado->load('usuario');
        return view('empleados.show', compact('empleado'));
    }

    public function edit(Empleado $empleado)
    {
        #$this->authorize('empleados.editar');
        $this->authorize('empleados.ver');
        return view('empleados.edit', compact('empleado'));
    }

    public function update(EmpleadoRequest $request, Empleado $empleado)
    {
        #$this->authorize('empleados.editar');
        $this->authorize('empleados.ver');
        $empleado->update($request->validated());
        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Empleado $empleado)
    {
        #$this->authorize('empleados.eliminar');
        $this->authorize('empleados.ver');

        if ($empleado->usuario) {
            return back()->with('error', 'No se puede eliminar: el empleado tiene un usuario asignado.');
        }

        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }
}
