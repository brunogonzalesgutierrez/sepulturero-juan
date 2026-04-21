<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrato extends Model
{
    use SoftDeletes;
    protected $fillable = ['cliente_id', 'espacio_id', 'fecha_contrato', 'monto_base', 'saldo_pendiente', 'estado', 'moneda', 'observacion'];
    protected $casts = ['fecha_contrato' => 'date'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function espacio()
    {
        return $this->belongsTo(Espacio::class);
    }
    public function venta()
    {
        return $this->hasOne(Venta::class);
    }
    public function inhumaciones()
    {
        return $this->hasMany(Inhumacion::class);
    }
    // app/Models/Contrato.php
    public function calcularTotalPagado()
    {
        $total = 0;
        // Sumar todos los pagos de todas las cuotas de todas las ventas
        foreach ($this->venta->pagoCredito->planPago->cuotas as $cuota) {
            $total += $cuota->pagos()->sum('monto_pagado');
        }
        return $total;
    }
}
