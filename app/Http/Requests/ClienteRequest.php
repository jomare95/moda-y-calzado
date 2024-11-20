<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $clienteId = $this->route('cliente') ? $this->route('cliente')->id_cliente : null;
        
        return [
            'nombre' => 'required|string|max:100',
            'tipo_documento' => 'required|in:DNI,RUC,CE,Pasaporte',
            'numero_documento' => 'required|string|max:20|unique:clientes,numero_documento,'.$clienteId.',id_cliente',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:clientes,email,'.$clienteId.',id_cliente',
            'fecha_nacimiento' => 'nullable|date',
            'estado' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'tipo_documento.required' => 'El tipo de documento es obligatorio',
            'numero_documento.required' => 'El número de documento es obligatorio',
            'numero_documento.unique' => 'Este número de documento ya está registrado',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'email.email' => 'Debe ingresar un correo electrónico válido'
        ];
    }
} 