@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detalles del Cliente</h1>
            <div class="flex space-x-2">
                <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('clientes.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-gray-600 text-sm font-medium">Nombre</h3>
                <p class="text-gray-900 mt-1">{{ $cliente->nombre }}</p>
            </div>

            <div>
                <h3 class="text-gray-600 text-sm font-medium">Documento</h3>
                <p class="text-gray-900 mt-1">{{ $cliente->tipo_documento }} - {{ $cliente->numero_documento }}</p>
            </div>

            <div>
                <h3 class="text-gray-600 text-sm font-medium">Teléfono</h3>
                <p class="text-gray-900 mt-1">{{ $cliente->telefono ?? 'No especificado' }}</p>
            </div>

            <div>
                <h3 class="text-gray-600 text-sm font-medium">Email</h3>
                <p class="text-gray-900 mt-1">{{ $cliente->email ?? 'No especificado' }}</p>
            </div>

            <div>
                <h3 class="text-gray-600 text-sm font-medium">Fecha de Nacimiento</h3>
                <p class="text-gray-900 mt-1">{{ $cliente->fecha_nacimiento ? date('d/m/Y', strtotime($cliente->fecha_nacimiento)) : 'No especificada' }}</p>
            </div>

            <div>
                <h3 class="text-gray-600 text-sm font-medium">Estado</h3>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mt-1
                    {{ $cliente->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $cliente->estado ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-gray-600 text-sm font-medium">Dirección</h3>
            <p class="text-gray-900 mt-1">{{ $cliente->direccion ?? 'No especificada' }}</p>
        </div>

        @if($cliente->ventas->count() > 0)
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Últimas Ventas</h2>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comprobante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cliente->ventas->take(5) as $venta)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $venta->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $venta->tipo_comprobante }} {{ $venta->serie_comprobante }}-{{ $venta->num_comprobante }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        ${{ number_format($venta->total, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $venta->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $venta->estado ? 'Activa' : 'Anulada' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 