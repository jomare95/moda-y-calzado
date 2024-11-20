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

        <form id="form-producto" method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data" class="space-y-6">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Marca *</label>
                        <div class="flex space-x-4 mb-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="marca_option" value="existente" checked 
                                       onclick="toggleMarcaInputs('existente')" class="form-radio">
                                <span class="ml-2">Marca Existente</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="marca_option" value="nueva" 
                                       onclick="toggleMarcaInputs('nueva')" class="form-radio">
                                <span class="ml-2">Nueva Marca</span>
                            </label>
                        </div>

                        <div id="marca_existente_div">
                            <select name="id_marca" id="id_marca" class="w-full rounded-md border-gray-300">
                                <option value="">Seleccionar Marca</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->id_marca }}">{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="marca_nueva_div" class="hidden">
                            <input type="text" name="marca_nueva" id="marca_nueva" 
                                   class="w-full rounded-md border-gray-300"
                                   placeholder="Nombre de la nueva marca">
                        </div>
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

            <!-- Precios -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio de Compra *</label>
                    <input type="number" 
                           name="precio_compra" 
                           step="0.01" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio de Venta *</label>
                    <input type="number" 
                           name="precio_venta" 
                           step="0.01" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                           required>
                </div>
            </div>

            <!-- Género -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Género *</label>
                <select name="genero" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                        required>
                    <option value="">Seleccionar Género</option>
                    <option value="Hombre">Hombre</option>
                    <option value="Mujer">Mujer</option>
                    <option value="Unisex">Unisex</option>
                    <option value="Niño">Niño</option>
                    <option value="Niña">Niña</option>
                </select>
            </div>

            <!-- Material -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Material</label>
                <input type="text" 
                       name="material" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <!-- Talla Principal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Talla Principal *</label>
                <input type="text" 
                       name="talla" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                       required>
            </div>

            <!-- Sección de Colores -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Colores y Talles</label>
                <button type="button" onclick="agregarColorTalle()" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg mb-2">
                    Agregar Color y Talle
                </button>
                <div id="colores-talles-container"></div>
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
function agregarColorTalle() {
    const container = document.getElementById('colores-talles-container');
    const div = document.createElement('div');
    div.className = 'flex space-x-2 mb-2';
    
    div.innerHTML = `
        <div class="w-1/4">
            <input type="text" 
                   name="colores[]" 
                   class="w-full rounded-md border-gray-300 shadow-sm" 
                   placeholder="Color" 
                   required>
        </div>
        <div class="w-1/4">
            <input type="text" 
                   name="talles[]" 
                   class="w-full rounded-md border-gray-300 shadow-sm" 
                   placeholder="Talle" 
                   required>
        </div>
        <div class="w-1/4">
            <input type="number" 
                   name="stocks[]" 
                   class="w-full rounded-md border-gray-300 shadow-sm" 
                   placeholder="Stock" 
                   min="0" 
                   required>
        </div>
        <div class="w-1/4">
            <button type="button" 
                    onclick="this.closest('div').parentElement.remove()" 
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(div);
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-producto');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Asegurarse de que los arrays estén sincronizados
            const colores = [];
            const talles = [];
            const stocks = [];
            
            document.querySelectorAll('#colores-talles-container > div').forEach(div => {
                const color = div.querySelector('input[name="colores[]"]').value;
                const talle = div.querySelector('input[name="talles[]"]').value;
                const stock = div.querySelector('input[name="stocks[]"]').value;
                
                if (color && talle && stock) {
                    colores.push(color);
                    talles.push(talle);
                    stocks.push(stock);
                }
            });
            
            formData.delete('colores[]');
            formData.delete('talles[]');
            formData.delete('stocks[]');
            
            colores.forEach(color => formData.append('colores[]', color));
            talles.forEach(talle => formData.append('talles[]', talle));
            stocks.forEach(stock => formData.append('stocks[]', stock));
            
            fetch('{{ route('productos.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message
                    }).then(() => {
                        window.location.href = '{{ route('productos.index') }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al guardar el producto'
                });
            });
        });
    }
});
</script>
@endpush
@endsection 