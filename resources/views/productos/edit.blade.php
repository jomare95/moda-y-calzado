@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Editar Producto</h1>
                <a href="{{ route('productos.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Volver
                </a>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('productos.update', $producto->id_producto) }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Código -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Código
                        </label>
                        <input type="text" 
                               name="codigo" 
                               value="{{ old('codigo', $producto->codigo) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('codigo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre
                        </label>
                        <input type="text" 
                               name="nombre" 
                               value="{{ old('nombre', $producto->nombre) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('nombre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Categoría -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Categoría
                        </label>
                        <select name="id_categoria" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}" 
                                    {{ old('id_categoria', $producto->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_categoria')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Marca -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Marca
                        </label>
                        <select name="id_marca" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccione una marca</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id_marca }}"
                                    {{ old('id_marca', $producto->id_marca) == $marca->id_marca ? 'selected' : '' }}>
                                    {{ $marca->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_marca')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Precios -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Precio de Compra
                        </label>
                        <input type="number" 
                               name="precio_compra" 
                               step="0.01" 
                               value="{{ old('precio_compra', $producto->precio_compra) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('precio_compra')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Precio de Venta
                        </label>
                        <input type="number" 
                               name="precio_venta" 
                               step="0.01" 
                               value="{{ old('precio_venta', $producto->precio_venta) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('precio_venta')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Stock
                        </label>
                        <input type="number" 
                               name="stock" 
                               value="{{ old('stock', $producto->stock) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('stock')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Stock Mínimo
                        </label>
                        <input type="number" 
                               name="stock_minimo" 
                               value="{{ old('stock_minimo', $producto->stock_minimo) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('stock_minimo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Características adicionales -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Género
                        </label>
                        <select name="genero" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccione un género</option>
                            @foreach(['Hombre', 'Mujer', 'Unisex', 'Niño', 'Niña'] as $genero)
                                <option value="{{ $genero }}" 
                                    {{ old('genero', $producto->genero) == $genero ? 'selected' : '' }}>
                                    {{ $genero }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipo de Producto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Producto</label>
                        <select name="tipo_producto" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="ropa" {{ $producto->tipo_producto == 'ropa' ? 'selected' : '' }}>Ropa</option>
                            <option value="calzado" {{ $producto->tipo_producto == 'calzado' ? 'selected' : '' }}>Calzado</option>
                        </select>
                    </div>

                    <!-- Color -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                        <input type="text" name="color" value="{{ old('color', $producto->color) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Talle -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Talle</label>
                        <input type="text" name="talle" value="{{ old('talle', $producto->talle) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea name="descripcion" 
                              rows="3" 
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('descripcion', $producto->descripcion) }}</textarea>
                </div>

                <!-- Imagen -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Imagen
                    </label>
                    @if($producto->imagen)
                        <div class="mb-2">
                            <img src="{{ asset('images/productos/' . $producto->imagen) }}" 
                                 alt="{{ $producto->nombre }}"
                                 class="w-32 h-32 object-cover rounded-lg">
                        </div>
                    @endif
                    <input type="file" 
                           name="imagen" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Secciones de Talles -->
                <div id="seccion_calzado" style="{{ $producto->tipo_producto == 'calzado' ? '' : 'display: none' }}" class="mt-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Talles de Calzado</h3>
                    <div class="grid grid-cols-4 gap-4">
                        @php
                            $tallesCalzado = range(35, 45);
                            $tallesActuales = $producto->talles->pluck('stock', 'talla')->toArray();
                        @endphp
                        
                        @foreach($tallesCalzado as $talle)
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" name="talles_calzado[]" value="{{ $talle }}" 
                                           id="talle_calzado_{{ $talle }}"
                                           {{ isset($tallesActuales[$talle]) ? 'checked' : '' }}
                                           class="rounded border-gray-300">
                                    <label for="talle_calzado_{{ $talle }}" class="ml-2">{{ $talle }}</label>
                                </div>
                                <input type="number" name="stock_talle_calzado[{{ $talle }}]" 
                                       value="{{ $tallesActuales[$talle] ?? 0 }}"
                                       min="0" class="w-full rounded-md border-gray-300">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="seccion_ropa" style="{{ $producto->tipo_producto == 'ropa' ? '' : 'display: none' }}" class="mt-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Talles de Ropa</h3>
                    <div class="grid grid-cols-4 gap-4">
                        @php
                            $tallesRopa = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
                        @endphp
                        
                        @foreach($tallesRopa as $talle)
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" name="talles_ropa[]" value="{{ $talle }}" 
                                           id="talle_ropa_{{ $talle }}"
                                           {{ isset($tallesActuales[$talle]) ? 'checked' : '' }}
                                           class="rounded border-gray-300">
                                    <label for="talle_ropa_{{ $talle }}" class="ml-2">{{ $talle }}</label>
                                </div>
                                <input type="number" name="stock_talle_ropa[{{ $talle }}]" 
                                       value="{{ $tallesActuales[$talle] ?? 0 }}"
                                       min="0" class="w-full rounded-md border-gray-300">
                            </div>
                        @endforeach
                    </div>

                    <!-- Colores para Ropa -->
                    <div class="mt-4">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Colores disponibles</h3>
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
                                $coloresActuales = $producto->colores->pluck('color')->toArray();
                            @endphp
                            
                            @foreach($coloresRopa as $nombre => $codigo)
                                <div class="flex items-center border rounded p-3">
                                    <input type="checkbox" name="colores_ropa[]" value="{{ $nombre }}" 
                                           id="color_ropa_{{ $nombre }}"
                                           {{ in_array($nombre, $coloresActuales) ? 'checked' : '' }}
                                           class="rounded border-gray-300">
                                    <label for="color_ropa_{{ $nombre }}" class="flex items-center ml-2">
                                        <span class="w-4 h-4 inline-block mr-2 border" style="background-color: {{ $codigo }};"></span>
                                        {{ $nombre }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 