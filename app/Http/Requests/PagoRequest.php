<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cuota_id'     => 'required|exists:cuotas,id',
            'empleado_id'  => 'required|exists:empleados,id',
            'fecha_pago'   => 'required|date',
            'monto_pagado' => 'required|numeric|min:0.01',
            'monto_interes' => 'nullable|numeric|min:0',
            'metodo_pago'  => 'required|in:efectivo,transferencia,tarjeta,qr',
            'comprobante'  => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'cuota_id.required'     => 'Seleccione una cuota.',
            'monto_pagado.required' => 'El monto es obligatorio.',
            'monto_pagado.min'      => 'El monto debe ser mayor a 0.',
            'metodo_pago.required'  => 'Seleccione el método de pago.',
        ];
    }
}
