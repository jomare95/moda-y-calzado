@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Encabezado -->
            <div class="flex justify-between items-center p-6 border-b">
                <h1 class="text-2xl font-bold text-gray-800">Detalles de la Compra</h1>
                <a href="{{ route('compras.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Volver
                </a>
            </div>

            <div class="p-6">
                <!-- Información básica -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Información General</h3>
                        <div class="mt-2 space-y-2">
                            <p><span class="font-medium">Número:</span> {{ $compra->tipo_comprobante }} {{ $compra->numero_comprobante }}</p>
                            <p><span class="font-medium">Fecha:</span> {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y H:i') }}</p>
                            <p><span class="font-medium">Proveedor:</span> {{ $compra->proveedor->razon_social }}</p>
                            <p><span class="font-medium">Estado:</span> {{ $compra->estado }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Totales</h3>
                        <div class="mt-2 space-y-2">
                            <p><span class="font-medium">Subtotal:</span> ${{ number_format($compra->subtotal, 2) }}</p>
                            <p><span class="font-medium">IVA:</span> ${{ number_format($compra->iva, 2) }}</p>
                            <p><span class="font-medium">Total:</span> ${{ number_format($compra->total, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la compra -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Productos Comprados</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Producto
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Precio Unitario
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($detalles as $detalle)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $detalle->producto->nombre }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $detalle->cantidad }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            ${{ number_format($detalle->precio_unitario, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            ${{ number_format($detalle->subtotal, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 