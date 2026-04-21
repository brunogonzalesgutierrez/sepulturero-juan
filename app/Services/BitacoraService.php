<?php

namespace App\Services;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class BitacoraService
{
    public static function registrar(string $tabla, int|string $registro, string $transaccion): void
    {
        $empleadoId = Auth::user()?->empleado_id;
        if (!$empleadoId) return;

        Bitacora::create([
            'empleado_id'    => $empleadoId,
            'fecha_hora'     => now(),
            'tabla_afectada' => $tabla,
            'nro_registro'   => (string) $registro,
            'transaccion'    => $transaccion,
        ]);
    }
}
