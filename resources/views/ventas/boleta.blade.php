@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl mx-auto">
        <!-- Encabezado de la Boleta -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">BOLETA DE VENTA</h1>
            <p class="text-gray-600">{{ $venta->numero_comprobante }}</p>
            <p class="text-gray-600">Fecha: {{ $venta->fecha_venta->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Información de la Empresa -->
        <div class="mb-6">
            <h2 class="font-bold">Moda y Calzado</h2>
            <p>Dirección: Av. Principal 123</p>
            <p>Teléfono: (01) 123-4567</p>
            <p>RUC: 20123456789</p>
        </div>

        <!-- Información del Cliente -->
        <div class="mb-6">
            <h3 class="font-bold mb-2">Datos del Cliente:</h3>
            <p>Cliente: {{ $venta->cliente ? $venta->cliente->nombre : 'Cliente General' }}</p>
            @if($venta->cliente)
                <p>Documento: {{ $venta->cliente->documento }}</p>
                <p>Dirección: {{ $venta->cliente->direccion }}</p>
            @endif
        </div>

        <!-- Detalle de Productos -->
        <div class="mb-6">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b-2 border-gray-300">
                        <th class="text-left py-2">Producto</th>
                        <th class="text-center py-2">Color</th>
                        <th class="text-center py-2">Talle</th>
                        <th class="text-center py-2">Cantidad</th>
                        <th class="text-right py-2">P.Unit</th>
                        <th class="text-right py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($venta->detalles as $detalle)
                    <tr class="border-b border-gray-200">
                        <td class="py-2">{{ $detalle->producto->nombre }}</td>
                        <td class="text-center py-2">{{ $detalle->color }}</td>
                        <td class="text-center py-2">{{ $detalle->talle }}</td>
                        <td class="text-center py-2">{{ $detalle->cantidad }}</td>
                        <td class="text-right py-2">${{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td class="text-right py-2">${{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <div class="flex justify-end mb-6">
            <div class="w-64">
                <div class="flex justify-between py-1">
                    <span>Subtotal:</span>
                    <span>${{ number_format($venta->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span>IVA (21%):</span>
                    <span>${{ number_format($venta->iva, 2) }}</span>
                </div>
                <div class="flex justify-between py-1 font-bold border-t border-gray-300">
                    <span>Total:</span>
                    <span>${{ number_format($venta->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Pie de Boleta -->
        <div class="text-center text-sm text-gray-600 mb-6">
            <p>¡Gracias por su compra!</p>
            <p>Este documento es una representación impresa de la Boleta Electrónica</p>
        </div>

        <!-- Botones de Acción -->
        <div class="flex justify-center gap-4">
            <button onclick="window.print()" 
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                <i class="fas fa-print mr-2"></i>Imprimir
            </button>
            <a href="{{ route('dashboard') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                <i class="fas fa-home mr-2"></i>Volver al Dashboard
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        /* Ocultar elementos durante la impresión */
        .no-print,
        nav,
        header,
        .navigation-menu,
        .navbar,
        .header-section,
        #header,
        .breadcrumb,
        .page-header,
        footer {
            display: none !important;
        }

        /* Ajustes para la impresión */
        body {
            padding: 0 !important;
            margin: 0 !important;
            background: white !important;
        }

        .container {
            max-width: none !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Asegurar que todo el contenido de la boleta sea visible */
        .bg-white {
            box-shadow: none !important;
            border: none !important;
        }

        /* Ajustar márgenes de impresión */
        @page {
            margin: 1cm;
        }
    }

    /* Estilos para la vista en pantalla */
    .print-only {
        display: none;
    }
    @media print {
        .print-only {
            display: block;
        }
    }
</style>
@endpush
@endsection 