<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CajaCierreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'monto_final' => 'required|numeric|min:0',
            'notas' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'monto_final.required' => 'El monto final es obligatorio',
            'monto_final.numeric' => 'El monto final debe ser un número',
            'monto_final.min' => 'El monto final no puede ser negativo'
        ];
    }
} 