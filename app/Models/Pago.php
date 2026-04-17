<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = ['cuota_id', 'empleado_id', 'fecha_pago', 'monto_pagado', 'monto_interes', 'metodo_pago', 'comprobante'];
    protected $casts = ['fecha_pago' => 'date'];

    public function cuota()
    {
        return $this->belongsTo(Cuota::class);
    }
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
