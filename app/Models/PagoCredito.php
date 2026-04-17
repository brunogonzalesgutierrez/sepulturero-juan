<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoCredito extends Model
{
    protected $fillable = ['venta_id', 'interes', 'monto_inicial'];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
    public function planPago()
    {
        return $this->hasOne(PlanPago::class);
    }
}
