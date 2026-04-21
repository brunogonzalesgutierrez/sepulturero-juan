<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InhumacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'espacio_id'       => 'required|exists:espacios,id',
            'contrato_id'      => 'required|exists:contratos,id',
            'nombre'           => 'required|string|max:100',
            'paterno'          => 'required|string|max:100',
            'materno'          => 'nullable|string|max:100',
            'fecha_nacimiento' => 'nullable|date|before:fecha_defuncion',
            'fecha_defuncion'  => 'required|date',
            'fecha_inhumacion' => 'required|date|after_or_equal:fecha_defuncion',
            'causa_muerte'     => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'espacio_id.required'        => 'Seleccione un espacio.',
            'contrato_id.required'       => 'Seleccione un contrato.',
            'fecha_inhumacion.after_or_equal' => 'La fecha de inhumación no puede ser antes de la defunción.',
            'fecha_nacimiento.before'    => 'La fecha de nacimiento debe ser anterior a la defunción.',
        ];
    }
}
