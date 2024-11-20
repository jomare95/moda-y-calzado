<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'tipo_comprobante' => 'required|in:Boleta,Factura,Ticket',
            'numero_comprobante' => 'required|string|max:50',
            'tipo_pago' => 'required|in:Efectivo,Tarjeta,Transferencia',
            'subtotal' => 'required|numeric|min:0',
            'igv' => 'required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
            'productos.*.subtotal' => 'required|numeric|min:0',
            'notas' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'id_cliente.required' => 'Debe seleccionar un cliente',
            'productos.required' => 'Debe agregar al menos un producto',
            'productos.min' => 'Debe agregar al menos un producto',
            'productos.*.cantidad.min' => 'La cantidad debe ser mayor a 0',
            'total.min' => 'El total no puede ser negativo'
        ];
    }
} 