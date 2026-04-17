<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compromiso extends Model
{
    protected $fillable = ['plan_pago_id', 'cuota_id', 'fecha_compromiso', 'plazo_dias', 'observacion'];
    protected $casts = ['fecha_compromiso' => 'date'];

    public function planPago()
    {
        return $this->belongsTo(PlanPago::class);
    }
    public function cuota()
    {
        return $this->belongsTo(Cuota::class);
    }
}
