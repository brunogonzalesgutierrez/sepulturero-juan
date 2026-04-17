<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContratoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id'      => 'required|exists:clientes,id',
            'espacio_id'      => 'required|exists:espacios,id',
            'fecha_contrato'  => 'required|date',
            'monto_base'      => 'required|numeric|min:0',
            'moneda'          => 'required|in:BOB,USD',
            'estado'          => 'required|in:activo,pagado,vencido,cancelado',
            'observacion'     => 'nullable|string|max:500',
        ];
    }
}
