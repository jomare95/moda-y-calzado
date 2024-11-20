@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detalle de Venta #{{ $venta->id_venta }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('ventas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
                <a href="{{ route('ventas.print', $venta->id_venta) }}" target="_blank" 
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-print mr-2"></i>Imprimir
                </a>
            </div>
        </div>

        <!-- Información de la Venta -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h2 class="text-lg font-semibold mb-3">Información de la Venta</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="mb-2"><span class="font-medium">Comprobante:</span> 
                        {{ $venta->tipo_comprobante }} #{{ $venta->numero_comprobante }}
                    </p>
                    <p class="mb-2"><span class="font-medium">Fecha:</span> 
                        {{ $venta->fecha_venta->format('d/m/Y H:i:s') }}
                    </p>
                    <p class="mb-2">
                        <span class="font-medium">Estado:</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $venta->estado_color }}">
                            {{ $venta->estado }}
                        </span>
                    </p>
                    <p class="mb-2"><span class="font-medium">Tipo de Pago:</span> 
                        {{ $venta->tipo_pago }}
                    </p>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-3">Información del Cliente</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    @if($venta->id_cliente)
                        <p class="mb-2"><span class="font-medium">Nombre:</span> 
                            {{ $venta->cliente->nombre }}
                        </p>
                        <p class="mb-2"><span class="font-medium">Documento:</span> 
                            {{ $venta->cliente->tipo_documento }} {{ $venta->cliente->numero_documento }}
                        </p>
                        @if($venta->cliente->telefono)
                            <p class="mb-2"><span class="font-medium">Teléfono:</span> 
                                {{ $venta->cliente->telefono }}
                            </p>
                        @endif
                        @if($venta->cliente->email)
                            <p class="mb-2"><span class="font-medium">Email:</span> 
                                {{ $venta->cliente->email }}
                            </p>
                        @endif
                    @else
                        <p class="text-gray-500">Venta Libre</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabla de Productos -->
        <div class="overflow-x-auto mb-6">
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
                            Precio Unit.
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subtotal
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($venta->detalles as $detalle)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $detalle->producto->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $detalle->cantidad }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                $ {{ number_format($detalle->precio_unitario, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                $ {{ number_format($detalle->subtotal, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <div class="flex justify-end">
            <div class="w-64">
                <div class="border-t pt-4">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Subtotal:</span>
                        <span>$ {{ number_format($venta->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">IVA (21%):</span>
                        <span>$ {{ number_format($venta->iva, 2) }}</span>
                    </div>
                    @if($venta->descuento > 0)
                        <div class="flex justify-between mb-2">
                            <span class="font-medium">Descuento:</span>
                            <span>$ {{ number_format($venta->descuento, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-lg border-t pt-2">
                        <span>Total:</span>
                        <span>$ {{ number_format($venta->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 