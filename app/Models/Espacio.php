<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Espacio extends Model
{
    use SoftDeletes;
    protected $fillable = ['cementerio_id', 'dimension_id', 'tipo_inhumacion_id', 'estado', 'precio_m2'];

    public function cementerio()
    {
        return $this->belongsTo(Cementerio::class);
    }
    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }
    public function tipoInhumacion()
    {
        return $this->belongsTo(TipoInhumacion::class);
    }
    public function direccion()
    {
        return $this->hasOne(Direccion::class);
    }
    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class);
    }
    public function inhumaciones()
    {
        return $this->hasMany(Inhumacion::class);
    }
    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
