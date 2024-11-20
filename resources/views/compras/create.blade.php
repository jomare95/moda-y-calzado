@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Nueva Compra</h1>
            <a href="{{ route('compras.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <form action="{{ route('compras.store') }}" method="POST" id="compraForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Proveedor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proveedor *</label>
                    <select name="id_proveedor" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('id_proveedor') border-red-500 @enderror"
                            required>
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

                <!-- Tipo de Comprobante -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Comprobante *</label>
                    <select name="tipo_comprobante" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Seleccionar tipo</option>
                        <option value="Factura">Factura</option>
                        <option value="Remito">Remito</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <!-- Número de Comprobante -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Número de Comprobante *</label>
                    <input type="text" name="numero_comprobante" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
            </div>

            <!-- Productos -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-4">Productos</h2>
                <div id="productos-container">
                    <div class="producto-item grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Producto *</label>
                            <select name="productos[0][id_producto]" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <option value="">Seleccionar producto</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id_producto }}">
                                        {{ $producto->nombre }} (Stock: {{ $producto->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad *</label>
                            <input type="number" name="productos[0][cantidad]" min="1" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Precio Unitario *</label>
                            <input type="number" name="productos[0][precio_unitario]" step="0.01" min="0" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex items-end">
                            <button type="button" onclick="eliminarProducto(this)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="agregarProducto()"
                        class="mt-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i> Agregar Producto
                </button>
            </div>

            <!-- Notas -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notas</label>
                <textarea name="notas" rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('compras.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
                    Registrar Compra
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let productoIndex = 1;

function agregarProducto() {
    const container = document.getElementById('productos-container');
    const template = document.querySelector('.producto-item').cloneNode(true);
    
    // Actualizar nombres de campos
    template.querySelectorAll('select, input').forEach(element => {
        element.name = element.name.replace('[0]', `[${productoIndex}]`);
        element.value = '';
    });
    
    container.appendChild(template);
    productoIndex++;
}

function eliminarProducto(button) {
    const items = document.querySelectorAll('.producto-item');
    if (items.length > 1) {
        button.closest('.producto-item').remove();
    }
}
</script>
@endpush
@endsection 