<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $productoId = $this->route('producto') ? $this->route('producto')->id_producto : null;
        
        return [
            'codigo' => 'required|string|max:50|unique:productos,codigo,'.$productoId.',id_producto',
            'nombre' => 'required|string|max:100',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'id_marca' => 'required|exists:marcas,id_marca',
            'descripcion' => 'nullable|string|max:255',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0|gt:precio_compra',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'talla' => 'required|string|max:10',
            'color' => 'required|string|max:50',
            'material' => 'nullable|string|max:50',
            'genero' => 'required|in:Hombre,Mujer,Unisex,Niño,Niña',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'estado' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'El código es obligatorio',
            'codigo.unique' => 'Este código ya está en uso',
            'nombre.required' => 'El nombre es obligatorio',
            'precio_venta.gt' => 'El precio de venta debe ser mayor al precio de compra',
            'imagen.image' => 'El archivo debe ser una imagen',
            'imagen.max' => 'La imagen no debe pesar más de 2MB'
        ];
    }
} 