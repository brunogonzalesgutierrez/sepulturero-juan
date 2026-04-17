<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanPago extends Model
{
    protected $fillable = ['pago_credito_id', 'fecha_inicio', 'fecha_fin', 'frecuencia', 'monto', 'interes_anual'];
    protected $casts = ['fecha_inicio' => 'date', 'fecha_fin' => 'date'];

    public function pagoCredito()
    {
        return $this->belongsTo(PagoCredito::class);
    }
    public function cuotas()
    {
        return $this->hasMany(Cuota::class);
    }
}
