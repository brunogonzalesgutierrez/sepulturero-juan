<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoContado extends Model
{
    protected $fillable = ['venta_id', 'descuento', 'metodo_pago'];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
