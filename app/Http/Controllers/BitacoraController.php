<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('bitacora.ver');

        $query = Bitacora::with('empleado');

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where('transaccion', 'like', "%$b%")
                ->orWhere('tabla_afectada', 'like', "%$b%")
                ->orWhereHas(
                    'empleado',
                    fn($q) =>
                    $q->where('nombre', 'like', "%$b%")
                        ->orWhere('paterno', 'like', "%$b%")
                );
        }

        if ($request->filled('tabla')) {
            $query->where('tabla_afectada', $request->tabla);
        }

        $bitacoras = $query->orderBy('fecha_hora', 'desc')->paginate(20)->withQueryString();
        $tablas    = Bitacora::distinct()->pluck('tabla_afectada')->sort()->values();

        return view('bitacora.index', compact('bitacoras', 'tablas'));
    }
}
