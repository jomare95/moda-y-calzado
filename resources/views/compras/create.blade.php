@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Nueva Compra</h1>

        <form id="compraForm">
            @csrf
            <!-- Datos del Proveedor -->
            <div class="mb-6">
                <label class="block mb-2">Proveedor *</label>
                <select name="id_proveedor" class="w-full rounded-md" required>
                    <option value="">Seleccionar Proveedor</option>
                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->razon_social }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Datos de la Compra -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block mb-2">Tipo Comprobante *</label>
                    <select name="tipo_comprobante" class="w-full rounded-md" required>
                        <option value="Factura">Factura</option>
                        <option value="Remito">Remito</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Número de Comprobante</label>
                    <input type="text" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" 
                           disabled 
                           placeholder="Se generará automáticamente">
                </div>
                <div>
                    <label class="block mb-2">Fecha *</label>
                    <input type="datetime-local" name="fecha_compra" class="w-full rounded-md" required>
                </div>
            </div>

            <!-- Productos -->
            <div class="mb-6">
                <label class="block mb-2">Productos</label>
                <div id="productos-container">
                    <div class="producto-item grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                        <select name="productos[]" class="producto-select" required>
                            <option value="">Seleccionar Producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id_producto }}" 
                                        data-precio="{{ $producto->precio_compra }}">
                                    {{ $producto->codigo }} - {{ $producto->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="cantidades[]" placeholder="Cantidad" class="cantidad" min="1" required>
                        <input type="number" name="precios[]" placeholder="Precio Unitario" class="precio" step="0.01" required>
                        <input type="number" name="subtotales[]" placeholder="Subtotal" class="subtotal" readonly>
                        <button type="button" class="eliminar-producto bg-red-500 text-white px-4 py-2 rounded">
                            Eliminar
                        </button>
                    </div>
                </div>
                <button type="button" id="agregar-producto" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">
                    Agregar Producto
                </button>
            </div>

            <!-- Totales -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block mb-2">Subtotal</label>
                    <input type="number" name="subtotal" id="subtotal" class="w-full rounded-md" readonly>
                </div>
                <div>
                    <label class="block mb-2">IVA</label>
                    <input type="number" name="iva" id="iva" class="w-full rounded-md" readonly>
                </div>
                <div>
                    <label class="block mb-2">Total</label>
                    <input type="number" name="total" id="total" class="w-full rounded-md" readonly>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded">
                    Guardar Compra
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('compraForm');
    const productosContainer = document.getElementById('productos-container');

    function initProductoEvents(item) {
        const cantidad = item.querySelector('.cantidad');
        const precio = item.querySelector('.precio');
        const subtotal = item.querySelector('.subtotal');
        const productoSelect = item.querySelector('.producto-select');
        
        // Cuando se selecciona un producto
        productoSelect.addEventListener('change', function() {
            const precioBase = this.options[this.selectedIndex].dataset.precio;
            if(precio) {
                precio.value = precioBase;
                calcularSubtotal();
            }
        });
        
        // Cuando cambia la cantidad o el precio
        [cantidad, precio].forEach(input => {
            input.addEventListener('input', calcularSubtotal);
        });
        
        function calcularSubtotal() {
            if(cantidad && precio && subtotal) {
                const cantidadVal = parseFloat(cantidad.value || 0);
                const precioVal = parseFloat(precio.value || 0);
                const subtotalVal = cantidadVal * precioVal;
                subtotal.value = subtotalVal.toFixed(2);
                calcularTotales();
            }
        }
    }

    function calcularTotales() {
        let subtotalGeneral = 0;
        
        document.querySelectorAll('.subtotal').forEach(input => {
            subtotalGeneral += parseFloat(input.value || 0);
        });
        
        const iva = subtotalGeneral * 0.21;
        const total = subtotalGeneral + iva;
        
        document.getElementById('subtotal').value = subtotalGeneral.toFixed(2);
        document.getElementById('iva').value = iva.toFixed(2);
        document.getElementById('total').value = total.toFixed(2);
    }

    // Inicializar productos existentes
    document.querySelectorAll('.producto-item').forEach(item => {
        initProductoEvents(item);
    });

    // Agregar nuevo producto
    document.getElementById('agregar-producto').addEventListener('click', function() {
        const productoItem = document.querySelector('.producto-item').cloneNode(true);
        productoItem.querySelectorAll('input').forEach(input => input.value = '');
        productoItem.querySelector('select').value = '';
        productosContainer.appendChild(productoItem);
        initProductoEvents(productoItem);
    });

    // Manejar envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Verificar que todos los subtotales estén calculados
        const subtotales = document.querySelectorAll('.subtotal');
        let isValid = true;
        
        subtotales.forEach(subtotal => {
            if(!subtotal.value || subtotal.value === '0' || subtotal.value === '0.00') {
                isValid = false;
            }
        });

        if(!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, asegúrese de que todos los productos tengan cantidad y precio'
            });
            return;
        }

        const formData = new FormData(this);
        
        fetch('/compras', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Compra registrada correctamente'
                }).then(() => {
                    window.location.href = '/compras';
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
                text: 'Hubo un problema al registrar la compra'
            });
        });
    });
});
</script>
@endpush
@endsection 