<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\ClienteRequest;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('clientes.ver');

        $query = Cliente::query();

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function ($q) use ($b) {
                $q->where('ci', 'like', "%$b%")
                    ->orWhere('nombre', 'like', "%$b%")
                    ->orWhere('paterno', 'like', "%$b%")
                    ->orWhere('materno', 'like', "%$b%")
                    ->orWhere('telefono', 'like', "%$b%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $clientes = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $this->authorize('clientes.crear');
        return view('clientes.create');
    }

    public function store(ClienteRequest $request)
    {
        $this->authorize('clientes.crear');
        Cliente::create($request->validated());
        return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        $this->authorize('clientes.ver');
        $cliente->load(['contratos.espacio', 'ventas']);
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $this->authorize('clientes.editar');
        return view('clientes.edit', compact('cliente'));
    }

    public function update(ClienteRequest $request, Cliente $cliente)
    {
        $this->authorize('clientes.editar');
        $cliente->update($request->validated());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $this->authorize('clientes.eliminar');
        if ($cliente->contratos()->count() > 0 || $cliente->ventas()->count() > 0) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede eliminar el cliente porque tiene contratos o ventas asociados .');
        }
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
