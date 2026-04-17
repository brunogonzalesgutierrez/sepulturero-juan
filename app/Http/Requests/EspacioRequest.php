<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EspacioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cementerio_id'       => 'required|exists:cementerios,id',
            'tipo_inhumacion_id'  => 'required|exists:tipo_inhumaciones,id',
            'estado'              => 'required|in:disponible,ocupado,mantenimiento,reservado',
            'precio_m2'           => 'required|numeric|min:0',
            // dimensión
            'ancho'               => 'required|numeric|min:0.01',
            'largo'               => 'required|numeric|min:0.01',
            // dirección
            'seccion'             => 'required|string|max:50',
            'numero'              => 'required|string|max:20',
            'calle'               => 'required|string|max:50',
            'fila'                => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'cementerio_id.required'      => 'Seleccione un cementerio.',
            'tipo_inhumacion_id.required' => 'Seleccione el tipo de espacio.',
            'precio_m2.required'          => 'El precio por m² es obligatorio.',
        ];
    }
}
