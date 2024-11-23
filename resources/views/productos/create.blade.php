@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Nuevo Producto</h1>
            <a href="{{ route('productos.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('productos.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Código -->
                <div>
                    <label for="codigo" class="block text-sm font-medium text-gray-700">Código *</label>
                    <input type="text" name="codigo" id="codigo" value="{{ old('codigo') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Categoría -->
                <div>
                    <label for="id_categoria" class="block text-sm font-medium text-gray-700">Categoría *</label>
                    <select name="id_categoria" id="id_categoria" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Seleccionar categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria') == $categoria->id_categoria ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Marca -->
                <div>
                    <label for="id_marca" class="block text-sm font-medium text-gray-700">Marca *</label>
                    <select name="id_marca" id="id_marca" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Seleccionar marca</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id_marca }}" {{ old('id_marca') == $marca->id_marca ? 'selected' : '' }}>
                                {{ $marca->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Proveedor -->
                <div>
                    <label for="id_proveedor" class="block text-sm font-medium text-gray-700">Proveedor *</label>
                    <div class="flex space-x-2">
                        <select name="id_proveedor" id="id_proveedor" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="">Seleccionar proveedor</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id_proveedor }}" {{ old('id_proveedor') == $proveedor->id_proveedor ? 'selected' : '' }}>
                                    {{ $proveedor->razon_social }}
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('proveedores.create') }}" 
                           class="mt-1 inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-bold rounded-lg">
                            <i class="fas fa-plus mr-2"></i> Nuevo
                        </a>
                    </div>
                </div>

                <!-- Precios -->
                <div>
                    <label for="precio_compra" class="block text-sm font-medium text-gray-700">Precio Compra *</label>
                    <input type="number" name="precio_compra" id="precio_compra" value="{{ old('precio_compra') }}" required step="0.01"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label for="precio_venta" class="block text-sm font-medium text-gray-700">Precio Venta *</label>
                    <input type="number" name="precio_venta" id="precio_venta" value="{{ old('precio_venta') }}" required step="0.01"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700">Stock Inicial *</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label for="stock_minimo" class="block text-sm font-medium text-gray-700">Stock Mínimo *</label>
                    <input type="number" name="stock_minimo" id="stock_minimo" value="{{ old('stock_minimo') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
            </div>

            <!-- Descripción -->
            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('descripcion') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Género -->
                <div>
                    <label for="genero" class="block text-sm font-medium text-gray-700">Género</label>
                    <select name="genero" id="genero"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Seleccionar género</option>
                        <option value="Hombre" {{ old('genero') == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                        <option value="Mujer" {{ old('genero') == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                        <option value="Unisex" {{ old('genero') == 'Unisex' ? 'selected' : '' }}>Unisex</option>
                        <option value="Niño" {{ old('genero') == 'Niño' ? 'selected' : '' }}>Niño</option>
                        <option value="Niña" {{ old('genero') == 'Niña' ? 'selected' : '' }}>Niña</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Selector de tipo de producto -->
                <div>
                    <label for="tipo_producto" class="block text-sm font-medium text-gray-700">Tipo de Producto *</label>
                    <select name="tipo_producto" id="tipo_producto" required onchange="mostrarTallesSegunTipo()"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Seleccionar tipo</option>
                        <option value="calzado" {{ old('tipo_producto') == 'calzado' ? 'selected' : '' }}>Calzado</option>
                        <option value="ropa" {{ old('tipo_producto') == 'ropa' ? 'selected' : '' }}>Ropa</option>
                    </select>
                </div>

                <!-- Sección Calzado -->
                <div id="seccion_calzado" class="mt-6" style="display: none;">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Talles Calzado -->
                        <div class="bg-white p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Talles disponibles *</label>
                            <div class="grid grid-cols-4 gap-4">
                                @foreach(range(35, 45) as $talle)
                                    <div class="border rounded p-3">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="talles_calzado[]" value="{{ $talle }}" 
                                                   id="talle_calzado_{{ $talle }}"
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label for="talle_calzado_{{ $talle }}" class="ml-2 text-sm">{{ $talle }}</label>
                                        </div>
                                        <input type="number" name="stock_talle_calzado[{{ $talle }}]" 
                                               placeholder="Stock"
                                               min="0"
                                               class="w-full text-sm rounded-md border-gray-300">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección Ropa -->
                <div id="seccion_ropa" class="mt-6" style="display: none;">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Talles Ropa -->
                        <div class="bg-white p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Talles disponibles *</label>
                            <div class="grid grid-cols-4 gap-4">
                                @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $talle)
                                    <div class="border rounded p-3">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="talles_ropa[]" value="{{ $talle }}" 
                                                   id="talle_ropa_{{ $talle }}"
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label for="talle_ropa_{{ $talle }}" class="ml-2 text-sm">{{ $talle }}</label>
                                        </div>
                                        <input type="number" name="stock_talle_ropa[{{ $talle }}]" 
                                               placeholder="Stock"
                                               min="0"
                                               class="w-full text-sm rounded-md border-gray-300">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Colores Ropa -->
                        <div class="bg-white p-4 rounded-lg mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Colores disponibles *</label>
                            <div class="grid grid-cols-4 gap-4">
                                @php
                                    $coloresRopa = [
                                        'Negro' => '#000000',
                                        'Blanco' => '#FFFFFF',
                                        'Rojo' => '#FF0000',
                                        'Azul' => '#0000FF',
                                        'Verde' => '#008000',
                                        'Amarillo' => '#FFFF00',
                                        'Rosa' => '#FFC0CB',
                                        'Morado' => '#800080',
                                        'Naranja' => '#FFA500',
                                        'Celeste' => '#87CEEB',
                                        'Gris' => '#808080',
                                        'Beige' => '#F5F5DC'
                                    ];
                                @endphp
                                
                                @foreach($coloresRopa as $nombre => $codigo)
                                    <div class="flex items-center border rounded p-3">
                                        <input type="checkbox" name="colores_ropa[]" value="{{ $nombre }}" 
                                               id="color_ropa_{{ $nombre }}"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="color_ropa_{{ $nombre }}" class="flex items-center ml-2 text-sm">
                                            <span class="w-4 h-4 inline-block mr-2 border" style="background-color: {{ $codigo }};"></span>
                                            {{ $nombre }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
                    Guardar Producto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 

@push('scripts')
<script>
function mostrarTallesSegunTipo() {
    const tipoProducto = document.getElementById('tipo_producto').value;
    const seccionCalzado = document.getElementById('seccion_calzado');
    const seccionRopa = document.getElementById('seccion_ropa');
    
    if (tipoProducto === 'calzado') {
        seccionCalzado.style.display = 'block';
        seccionRopa.style.display = 'none';
    } else if (tipoProducto === 'ropa') {
        seccionCalzado.style.display = 'none';
        seccionRopa.style.display = 'block';
    } else {
        seccionCalzado.style.display = 'none';
        seccionRopa.style.display = 'none';
    }
}

// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    mostrarTallesSegunTipo();
});
</script>
@endpush 