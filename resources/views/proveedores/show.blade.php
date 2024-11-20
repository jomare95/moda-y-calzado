@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalles del Proveedor</h1>
        <div>
            <a href="{{ route('proveedores.edit', $proveedor->id_proveedor) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
            <a href="{{ route('proveedores.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-gray-600 text-sm font-medium">Nombre</h3>
                <p class="text-gray-900">{{ $proveedor->nombre }}</p>
            </div>

            <div>
                <h3 class="text-gray-600 text-sm font-medium">Documento</h3>
                <p class="text-gray-900">{{ $proveedor->tipo_documento }}: {{ $proveedor->numero_documento }}</p>
            </div>

            <div>
                <h3 class="text-gray-600 text-sm font-medium">Teléfono</h3>
                <p class="text-gray-900">{{ $proveedor->telefono ?: 'No especificado' }}</p>
            </div>

            <div>
                <h3 class="text-gray-600 text-sm font-medium">Email</h3>
                <p class="text-gray-900">{{ $proveedor->email ?: 'No especificado' }}</p>
            </div>

            <div>
                <h3 class="text-gray-600 text-sm font-medium">Dirección</h3>
                <p class="text-gray-900">{{ $proveedor->direccion ?: 'No especificada' }}</p>
            </div>

            <div>
                <h3 class="text-gray-600 text-sm font-medium">Estado</h3>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {{ $proveedor->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $proveedor->estado ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
        </div>

        @if($proveedor->notas)
            <div class="mt-6">
                <h3 class="text-gray-600 text-sm font-medium">Notas</h3>
                <p class="text-gray-900 mt-1">{{ $proveedor->notas }}</p>
            </div>
        @endif
    </div>
</div>
@endsection 