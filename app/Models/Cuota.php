<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $fillable = ['plan_pago_id', 'nro_cuota', 'estado', 'fecha_vencimiento', 'monto'];
    protected $casts = ['fecha_vencimiento' => 'date'];

    public function planPago()
    {
        return $this->belongsTo(PlanPago::class);
    }
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
    public function compromiso()
    {
        return $this->hasOne(Compromiso::class);
    }
}
