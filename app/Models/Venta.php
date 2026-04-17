<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use SoftDeletes;
    protected $fillable = ['contrato_id', 'cliente_id', 'empleado_id', 'fecha_venta', 'precio_total', 'tipo_venta', 'moneda'];
    protected $casts = ['fecha_venta' => 'date'];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
    public function pagoContado()
    {
        return $this->hasOne(PagoContado::class);
    }
    public function pagoCredito()
    {
        return $this->hasOne(PagoCredito::class);
    }
}
