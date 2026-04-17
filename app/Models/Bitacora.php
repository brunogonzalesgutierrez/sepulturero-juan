<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $fillable = ['empleado_id', 'fecha_hora', 'tabla_afectada', 'nro_registro', 'transaccion'];
    protected $casts = ['fecha_hora' => 'datetime'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
