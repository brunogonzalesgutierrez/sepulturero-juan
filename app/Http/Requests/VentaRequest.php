<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'contrato_id'  => 'required|exists:contratos,id',
            'cliente_id'   => 'required|exists:clientes,id',
            'empleado_id'  => 'required|exists:empleados,id',
            'fecha_venta'  => 'required|date',
            'precio_total' => 'required|numeric|min:0',
            'tipo_venta'   => 'required|in:contado,credito',
            'moneda'       => 'required|in:BOB,USD',
        ];

        // Reglas adicionales según tipo de venta
        if ($this->tipo_venta === 'contado') {
            $rules['descuento']    = 'nullable|numeric|min:0';
            $rules['metodo_pago']  = 'required|in:efectivo,transferencia,tarjeta,qr';
        }

        if ($this->tipo_venta === 'credito') {
            $rules['interes']       = 'required|numeric|min:0|max:100';
            $rules['frecuencia']    = 'required|in:semanal,quincenal,mensual';
            $rules['fecha_inicio']  = 'required|date';
            $rules['fecha_fin']     = 'required|date|after:fecha_inicio';
            $rules['nro_cuotas']    = 'required|integer|min:1|max:360';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'contrato_id.required' => 'Seleccione un contrato.',
            'interes.required'     => 'La tasa de interés es obligatoria para ventas a crédito.',
            'nro_cuotas.required'  => 'El número de cuotas es obligatorio.',
            'fecha_fin.after'      => 'La fecha fin debe ser posterior a la fecha inicio.',
        ];
    }
}
