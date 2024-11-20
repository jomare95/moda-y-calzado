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

        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <strong class="font-bold">¡Hay errores en el formulario!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Información Básica -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Código *</label>
                    <input type="text" name="codigo" value="{{ old('codigo') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                           required>
                </div>

                <!-- Categoría y Marca -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                        <select name="id_categoria" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                            <option value="">Seleccionar Categoría</option>
                            @if($categorias && $categorias->count() > 0)
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id_categoria }}">
                                        {{ $categoria->nombre ?? 'Sin nombre' }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>No hay categorías disponibles</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo *</label>
                        <select name="tipo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                            <option value="">Seleccionar Tipo</option>
                            <option value="Ropa">Ropa</option>
                            <option value="Calzado">Calzado</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Imagen -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Imagen del Producto</label>
                <input type="file" name="imagen" accept="image/*"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                <p class="text-xs text-gray-500 mt-1">Formatos permitidos: JPG, PNG. Máximo 2MB</p>
            </div>

            <!-- Descripción -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                <textarea name="descripcion" rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('descripcion') }}</textarea>
            </div>

            <!-- Proveedor -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Proveedor
                </label>
                <select name="id_proveedor" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Seleccione un proveedor</option>
                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->id_proveedor }}" {{ old('id_proveedor') == $proveedor->id_proveedor ? 'selected' : '' }}>
                            {{ $proveedor->razon_social }} - {{ $proveedor->numero_documento }}
                        </option>
                    @endforeach
                </select>
                @error('id_proveedor')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Select de Marca -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Marca
                </label>
                <div class="space-y-2">
                    <!-- Radio buttons para elegir entre marca existente o nueva -->
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" 
                                   name="marca_option" 
                                   value="existente" 
                                   class="form-radio"
                                   checked
                                   onclick="toggleMarcaInputs('existente')">
                            <span class="ml-2">Marca Existente</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" 
                                   name="marca_option" 
                                   value="nueva" 
                                   class="form-radio"
                                   onclick="toggleMarcaInputs('nueva')">
                            <span class="ml-2">Nueva Marca</span>
                        </label>
                    </div>

                    <!-- Select para marcas existentes -->
                    <div id="marca_existente_div">
                        <select name="id_marca" 
                                id="id_marca"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccione una marca</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id_marca }}" {{ old('id_marca') == $marca->id_marca ? 'selected' : '' }}>
                                    {{ $marca->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input para nueva marca -->
                    <div id="marca_nueva_div" class="hidden">
                        <input type="text" 
                               name="marca_nueva" 
                               id="marca_nueva"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Ingrese el nombre de la nueva marca">
                    </div>
                </div>
                @error('id_marca')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @error('marca_nueva')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Agregar después de los campos existentes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Colores Múltiples -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Colores Disponibles
                    </label>
                    <div class="space-y-2">
                        <div id="colores-container">
                            <div class="flex space-x-2 mb-2">
                                <input type="text" 
                                       name="colores[]" 
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Ingrese un color">
                                <button type="button" 
                                        onclick="agregarColor()"
                                        class="bg-blue-500 text-white px-3 py-1 rounded-lg">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('colores.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Talles con Stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Talles y Stock
                    </label>
                    <div class="space-y-2">
                        <div id="talles-container">
                            <div class="flex space-x-2 mb-2">
                                <input type="text" 
                                       name="talles[]" 
                                       class="w-1/2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Talle">
                                <input type="number" 
                                       name="stocks[]" 
                                       class="w-1/2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Stock">
                                <button type="button" 
                                        onclick="agregarTalle()"
                                        class="bg-blue-500 text-white px-3 py-1 rounded-lg">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('talles.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('stocks.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('productos.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
                    Guardar Producto
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calcular precio de venta automáticamente (30% de ganancia)
    const precioCompra = document.querySelector('[name="precio_compra"]');
    const precioVenta = document.querySelector('[name="precio_venta"]');
    
    precioCompra.addEventListener('input', function() {
        const compra = parseFloat(this.value) || 0;
        const ganancia = compra * 0.30; // 30% de ganancia
        precioVenta.value = (compra + ganancia).toFixed(2);
    });
});

function toggleMarcaInputs(option) {
    const existenteDiv = document.getElementById('marca_existente_div');
    const nuevaDiv = document.getElementById('marca_nueva_div');
    const marcaSelect = document.getElementById('id_marca');
    const marcaNuevaInput = document.getElementById('marca_nueva');

    if (option === 'existente') {
        existenteDiv.classList.remove('hidden');
        nuevaDiv.classList.add('hidden');
        marcaNuevaInput.value = '';
    } else {
        existenteDiv.classList.add('hidden');
        nuevaDiv.classList.remove('hidden');
        marcaSelect.value = '';
    }
}

function agregarColor() {
    const container = document.getElementById('colores-container');
    const div = document.createElement('div');
    div.className = 'flex space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" 
               name="colores[]" 
               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
               placeholder="Ingrese un color">
        <button type="button" 
                onclick="this.parentElement.remove()"
                class="bg-red-500 text-white px-3 py-1 rounded-lg">
            -
        </button>
    `;
    container.appendChild(div);
}

function agregarTalle() {
    const container = document.getElementById('talles-container');
    const div = document.createElement('div');
    div.className = 'flex space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" 
               name="talles[]" 
               class="w-1/2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
               placeholder="Talle">
        <input type="number" 
               name="stocks[]" 
               class="w-1/2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
               placeholder="Stock">
        <button type="button" 
                onclick="this.parentElement.remove()"
                class="bg-red-500 text-white px-3 py-1 rounded-lg">
            -
        </button>
    `;
    container.appendChild(div);
}
</script>
@endpush
@endsection 