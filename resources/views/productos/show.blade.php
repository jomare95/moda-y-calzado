@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Encabezado -->
            <div class="flex justify-between items-center p-6 border-b">
                <h1 class="text-2xl font-bold text-gray-800">Detalles del Producto</h1>
                <a href="{{ route('productos.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Volver
                </a>
            </div>

            <div class="p-6">
                <!-- Información básica -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Detalles del producto -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Información General</h3>
                            <div class="mt-2 space-y-2">
                                <p><span class="font-medium">Código:</span> {{ $producto->codigo }}</p>
                                <p><span class="font-medium">Nombre:</span> {{ $producto->nombre }}</p>
                                <p><span class="font-medium">Categoría:</span> {{ optional($producto->categoria)->nombre ?? 'No especificada' }}</p>
                                <p><span class="font-medium">Marca:</span> {{ optional($producto->marca)->nombre ?? 'No especificada' }}</p>
                                <p><span class="font-medium">Tipo:</span> {{ ucfirst($producto->tipo_producto) }}</p>
                                <p><span class="font-medium">Género:</span> {{ $producto->genero ?? 'No especificado' }}</p>
                                <p><span class="font-medium">Estado:</span> 
                                    <span class="px-2 py-1 text-sm rounded-full 
                                        {{ $producto->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Precios -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Precios</h3>
                            <div class="mt-2 space-y-2">
                                <p><span class="font-medium">Precio de Compra:</span> ${{ number_format($producto->precio_compra, 2) }}</p>
                                <p><span class="font-medium">Precio de Venta:</span> ${{ number_format($producto->precio_venta, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stock y Talles -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Stock por Talle</h3>
                            <div class="mt-2 grid grid-cols-3 gap-2">
                                @foreach($producto->talles as $talle)
                                    <div class="border rounded p-2 text-center">
                                        <span class="font-medium">Talle {{ $talle->talla }}</span>
                                        <p class="{{ $talle->stock <= 0 ? 'text-red-600' : 'text-green-600' }}">
                                            Stock: {{ $talle->stock }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Colores -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Colores Disponibles</h3>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($producto->colores as $color)
                                    <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">
                                        {{ $color->color }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Stock Total -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Stock Total</h3>
                            <div class="mt-2">
                                <p class="{{ $producto->stock <= $producto->stock_minimo ? 'text-red-600' : 'text-green-600' }} font-bold">
                                    {{ $producto->stock }} unidades
                                </p>
                                <p class="text-sm text-gray-600">Stock mínimo: {{ $producto->stock_minimo }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descripción -->
                @if($producto->descripcion)
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Descripción</h3>
                    <p class="text-gray-600">{{ $producto->descripcion }}</p>
                </div>
                @endif

                <!-- Botones de acción -->
                <div class="border-t pt-6 mt-6 flex justify-end space-x-4">
                    <a href="{{ route('productos.edit', $producto->id_producto) }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                        Editar Producto
                    </a>
                    <form action="{{ route('productos.destroy', $producto->id_producto) }}" 
                          method="POST" 
                          class="inline-block"
                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                            Eliminar Producto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 