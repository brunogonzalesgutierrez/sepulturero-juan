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
<<<<<<< HEAD
            'cementerio_id'      => 'required|exists:cementerios,id',
            'tipo_inhumacion_id' => 'required|exists:tipo_inhumaciones,id',
            'estado'             => 'required|in:disponible,ocupado,mantenimiento,reservado',
            'precio_m2'          => 'required|numeric|min:0',
            'ancho'              => 'required|numeric|min:0.01',
            'largo'              => 'required|numeric|min:0.01',
            'seccion'            => 'required|string|max:50',
            'numero'             => 'required|string|max:20',
            'calle'              => 'required|string|max:50',
            'fila'               => 'required|string|max:50',
=======
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
>>>>>>> 665fe70f9df4c506ced3c6beb45900d4c0698f0c
        ];
    }

    public function messages(): array
    {
        return [
            'cementerio_id.required'      => 'Seleccione un cementerio.',
            'tipo_inhumacion_id.required' => 'Seleccione el tipo de espacio.',
            'precio_m2.required'          => 'El precio por m² es obligatorio.',
<<<<<<< HEAD

=======
>>>>>>> 665fe70f9df4c506ced3c6beb45900d4c0698f0c
        ];
    }
}
