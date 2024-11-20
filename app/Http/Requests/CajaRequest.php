<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CajaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'monto_inicial' => 'required|numeric|min:0',
            'fecha_apertura' => 'required|date',
            'notas' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'monto_inicial.required' => 'El monto inicial es obligatorio',
            'monto_inicial.numeric' => 'El monto inicial debe ser un número',
            'monto_inicial.min' => 'El monto inicial no puede ser negativo',
            'fecha_apertura.required' => 'La fecha de apertura es obligatoria',
            'fecha_apertura.date' => 'La fecha debe ser válida'
        ];
    }
} 