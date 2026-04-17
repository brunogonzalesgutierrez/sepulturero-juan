<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $fillable = ['venta_id', 'espacio_id', 'precio_unitario'];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
    public function espacio()
    {
        return $this->belongsTo(Espacio::class);
    }
}
