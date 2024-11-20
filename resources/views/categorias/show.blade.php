@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detalles de la Categoría</h1>
            <div class="flex space-x-2">
                <a href="{{ route('categorias.edit', $categoria->id_categoria) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('categorias.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-gray-600 text-sm font-medium">Nombre</h3>
                <p class="text-gray-900 mt-1">{{ $categoria->nombre }}</p>
            </div>

            <div>
                <h3 class="text-gray-600 text-sm font-medium">Estado</h3>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mt-1
                    {{ $categoria->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $categoria->estado ? 'Activo' : 'Inactivo' }}
                </span>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-gray-600 text-sm font-medium">Descripción</h3>
                <p class="text-gray-900 mt-1">{{ $categoria->descripcion ?? 'Sin descripción' }}</p>
            </div>
        </div>

        @if($categoria->productos->count() > 0)
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Productos en esta categoría</h2>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Código
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stock
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($categoria->productos as $producto)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $producto->codigo }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $producto->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $producto->stock }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $producto->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $producto->estado ? 'Activo' : 'Inactivo' }}
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