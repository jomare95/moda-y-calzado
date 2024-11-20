@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Nueva Venta</h1>
            <a href="{{ route('ventas.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <form id="ventaForm" method="POST" action="{{ route('ventas.store') }}">
            @csrf
            
            <!-- Información Principal -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Cliente -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                    <select name="id_cliente" id="id_cliente" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Venta Libre</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id_cliente }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_cliente')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tipo de Comprobante -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo Comprobante</label>
                    <select name="tipo_comprobante" id="tipo_comprobante" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="Boleta">Boleta</option>
                        <option value="Factura">Factura</option>
                        <option value="Ticket">Ticket</option>
                    </select>
                </div>

                <!-- Tipo de Pago -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Pago</label>
                    <select name="tipo_pago" id="tipo_pago" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Tarjeta">Tarjeta</option>
                        <option value="Transferencia">Transferencia</option>
                    </select>
                </div>
            </div>

            <!-- Búsqueda de Productos -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Producto</label>
                        <select class="producto-select form-select w-full" onchange="cargarDetallesProducto(this)">
                            <option value="">Seleccione un producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id_producto }}">
                                    {{ $producto->codigo }} - {{ $producto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Color</label>
                        <select class="color-select form-select w-full" disabled>
                            <option value="">Seleccione color</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Talle</label>
                        <select class="talle-select form-select w-full" disabled>
                            <option value="">Seleccione talle</option>
                        </select>
                        <span class="text-sm text-gray-500 stock-disponible"></span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input type="number" 
                               class="cantidad-input form-input w-full" 
                               min="1" 
                               disabled>
                    </div>
                </div>

                <button type="button" 
                        onclick="agregarProducto()" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg disabled:opacity-50"
                        disabled>
                    Agregar Producto
                </button>
            </div>

            <!-- Tabla de productos seleccionados -->
            <table id="tabla-productos" class="min-w-full mt-4">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Color</th>
                        <th>Talle</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <!-- Campo oculto para los productos -->
            <input type="hidden" name="productos" id="productos_seleccionados">

            <!-- Totales -->
            <div class="flex justify-end mt-6">
                <div class="w-64 space-y-2">
                    <div class="flex justify-between">
                        <span class="font-medium">Subtotal:</span>
                        <span id="subtotal_display">$ 0.00</span>
                        <input type="hidden" name="subtotal" id="subtotal_input" value="0">
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">IVA (21%):</span>
                        <span id="iva_display">$ 0.00</span>
                        <input type="hidden" name="iva" id="iva_input" value="0">
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Descuento:</span>
                        <input type="number" 
                               name="descuento" 
                               id="descuento" 
                               value="0" 
                               min="0" 
                               step="0.01" 
                               class="w-24 text-right rounded-md border-gray-300">
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-200">
                        <span class="font-bold">Total:</span>
                        <span id="total_display" class="font-bold">$ 0.00</span>
                        <input type="hidden" name="total" id="total_input" value="0">
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('ventas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Registrar Venta
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let productosSeleccionados = [];

function cargarDetallesProducto(select) {
    const productoId = select.value;
    console.log('ID del producto seleccionado:', productoId);

    const container = select.closest('.bg-gray-50');
    const colorSelect = container.querySelector('.color-select');
    const talleSelect = container.querySelector('.talle-select');
    const cantidadInput = container.querySelector('.cantidad-input');
    
    // Resetear y deshabilitar los selects
    colorSelect.innerHTML = '<option value="">Seleccione color</option>';
    talleSelect.innerHTML = '<option value="">Seleccione talle</option>';
    colorSelect.disabled = true;
    talleSelect.disabled = true;
    cantidadInput.disabled = true;

    if (!productoId) return;

    fetch(`/api/productos/${productoId}/detalles`)
        .then(response => response.json())
        .then(data => {
            console.log('Datos recibidos del servidor:', data);
            
            if (data.colores && data.colores.length > 0) {
                colorSelect.disabled = false;
                data.colores.forEach(color => {
                    console.log('Agregando color:', color);
                    colorSelect.add(new Option(color.color, color.color));
                });
            }

            // Guardar los datos en el container
            container.dataset.talles = JSON.stringify(data.talles);
            container.dataset.precio = data.precio;
            console.log('Datos guardados en dataset:', {
                talles: container.dataset.talles,
                precio: container.dataset.precio
            });

            // Agregar el evento change al select de colores
            colorSelect.onchange = function() {
                console.log('Color seleccionado:', this.value);
                actualizarTalles(this);
            };
        })
        .catch(error => {
            console.error('Error al cargar detalles:', error);
        });
}

function actualizarTalles(colorSelect) {
    try {
        console.log('Actualizando talles...');
        const container = colorSelect.closest('.bg-gray-50');
        const talleSelect = container.querySelector('.talle-select');
        const cantidadInput = container.querySelector('.cantidad-input');
        
        // Obtener los talles del dataset y verificar que existan
        console.log('Dataset talles:', container.dataset.talles);
        const talles = JSON.parse(container.dataset.talles || '[]');
        console.log('Talles parseados:', talles);

        // Resetear el select de talles
        talleSelect.innerHTML = '<option value="">Seleccione talle</option>';
        cantidadInput.disabled = true;
        
        if (colorSelect.value) {
            talleSelect.disabled = false;
            // Filtrar talles por el color seleccionado si es necesario
            talles.forEach(talle => {
                console.log('Agregando talle:', talle);
                const option = new Option(
                    `${talle.talla} (Stock: ${talle.stock})`, 
                    talle.talla
                );
                option.dataset.stock = talle.stock;
                talleSelect.add(option);
            });

            // Agregar el evento change al select de talles
            talleSelect.onchange = function() {
                console.log('Talle seleccionado:', this.value);
                actualizarStockDisponible(this);
            };
        } else {
            talleSelect.disabled = true;
        }

        verificarCamposCompletos();
    } catch (error) {
        console.error('Error en actualizarTalles:', error);
        console.error('Error detallado:', {
            mensaje: error.message,
            stack: error.stack
        });
    }
}

function actualizarStockDisponible(talleSelect) {
    try {
        console.log('Actualizando stock disponible...');
        const container = talleSelect.closest('.bg-gray-50');
        const cantidadInput = container.querySelector('.cantidad-input');
        const stockSpan = container.querySelector('.stock-disponible');
        const selectedOption = talleSelect.selectedOptions[0];

        console.log('Opción seleccionada:', selectedOption);

        if (selectedOption && selectedOption.dataset.stock) {
            const stock = parseInt(selectedOption.dataset.stock);
            console.log('Stock disponible:', stock);
            
            stockSpan.textContent = `Stock disponible: ${stock}`;
            cantidadInput.max = stock;
            cantidadInput.disabled = false;
            cantidadInput.value = 1;
            cantidadInput.min = 1;

            // Agregar evento para la cantidad
            cantidadInput.onchange = verificarCamposCompletos;
        } else {
            cantidadInput.disabled = true;
            stockSpan.textContent = '';
        }

        verificarCamposCompletos();
    } catch (error) {
        console.error('Error en actualizarStockDisponible:', error);
    }
}

function verificarCamposCompletos() {
    try {
        // Buscar dentro del contenedor de productos
        const container = document.querySelector('.bg-gray-50');
        if (!container) {
            console.error('No se encontró el contenedor de productos');
            return;
        }

        const productoSelect = container.querySelector('.producto-select');
        const colorSelect = container.querySelector('.color-select');
        const talleSelect = container.querySelector('.talle-select');
        const cantidadInput = container.querySelector('.cantidad-input');
        const btnAgregar = container.querySelector('button[onclick="agregarProducto()"]');

        console.log('Elementos encontrados:', { 
            producto: productoSelect?.value,
            color: colorSelect?.value,
            talle: talleSelect?.value,
            cantidad: cantidadInput?.value,
            boton: btnAgregar
        });

        if (!productoSelect || !colorSelect || !talleSelect || !cantidadInput || !btnAgregar) {
            console.error('Elementos faltantes:', {
                producto: !productoSelect,
                color: !colorSelect,
                talle: !talleSelect,
                cantidad: !cantidadInput,
                boton: !btnAgregar
            });
            return;
        }

        const todosCompletos = 
            productoSelect.value && 
            colorSelect.value && 
            talleSelect.value && 
            cantidadInput.value && 
            parseInt(cantidadInput.value) > 0;

        btnAgregar.disabled = !todosCompletos;
        
        console.log('Estado de campos:', {
            productoValue: productoSelect.value,
            colorValue: colorSelect.value,
            talleValue: talleSelect.value,
            cantidadValue: cantidadInput.value,
            todosCompletos: todosCompletos
        });

    } catch (error) {
        console.error('Error en verificarCamposCompletos:', error);
    }
}

function actualizarTotal() {
    const subtotal = productosSeleccionados.reduce((sum, producto) => sum + producto.subtotal, 0);
    const descuento = parseFloat(document.getElementById('descuento').value) || 0;
    const iva = (subtotal - descuento) * 0.21; // 21% de IVA
    const total = subtotal + iva - descuento;

    // Actualizar displays
    document.getElementById('subtotal_display').textContent = `$ ${subtotal.toFixed(2)}`;
    document.getElementById('iva_display').textContent = `$ ${iva.toFixed(2)}`;
    document.getElementById('total_display').textContent = `$ ${total.toFixed(2)}`;

    // Actualizar inputs hidden
    document.getElementById('subtotal_input').value = subtotal;
    document.getElementById('iva_input').value = iva;
    document.getElementById('total_input').value = total;
}

// Asegurarse de que los eventos se agreguen cuando los elementos existan
document.addEventListener('DOMContentLoaded', function() {
    const cantidadInput = document.querySelector('.cantidad-input');
    if (cantidadInput) {
        cantidadInput.addEventListener('input', verificarCamposCompletos);
    }

    const descuentoInput = document.getElementById('descuento');
    if (descuentoInput) {
        descuentoInput.addEventListener('input', actualizarTotal);
    }
});

function agregarProducto() {
    try {
        console.log('Iniciando agregar producto...'); // Debug

        // Buscar el contenedor correcto
        const container = document.querySelector('.bg-gray-50');
        if (!container) {
            console.error('No se encontró el contenedor principal');
            return;
        }

        // Obtener los elementos del formulario
        const productoSelect = container.querySelector('.producto-select');
        const colorSelect = container.querySelector('.color-select');
        const talleSelect = container.querySelector('.talle-select');
        const cantidadInput = container.querySelector('.cantidad-input');

        // Verificar que todos los elementos existan y tengan valores
        if (!productoSelect?.value || !colorSelect?.value || !talleSelect?.value || !cantidadInput?.value) {
            console.error('Valores de los campos:', {
                producto: productoSelect?.value,
                color: colorSelect?.value,
                talle: talleSelect?.value,
                cantidad: cantidadInput?.value
            });
            alert('Por favor complete todos los campos');
            return;
        }

        // Obtener el precio del dataset
        const precio = parseFloat(container.dataset.precio);
        if (!precio) {
            console.error('No se encontró el precio del producto');
            return;
        }

        const producto = {
            producto_id: productoSelect.value,
            nombre: productoSelect.selectedOptions[0].text,
            color: colorSelect.value,
            talle: talleSelect.value,
            cantidad: parseInt(cantidadInput.value),
            precio: precio,
            subtotal: precio * parseInt(cantidadInput.value)
        };

        console.log('Producto a agregar:', producto); // Debug

        // Agregar a la tabla de productos
        const tabla = document.querySelector('#tabla-productos tbody');
        if (!tabla) {
            console.error('No se encontró la tabla de productos');
            return;
        }

        tabla.innerHTML += `
            <tr>
                <td class="px-4 py-2">${producto.nombre}</td>
                <td class="px-4 py-2">${producto.color}</td>
                <td class="px-4 py-2">${producto.talle}</td>
                <td class="px-4 py-2">${producto.cantidad}</td>
                <td class="px-4 py-2">$${producto.precio.toFixed(2)}</td>
                <td class="px-4 py-2">$${producto.subtotal.toFixed(2)}</td>
                <td class="px-4 py-2">
                    <button type="button" onclick="eliminarProducto(this)" 
                            class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        // Agregar al array de productos
        productosSeleccionados.push(producto);
        
        // Actualizar el input hidden con los productos
        const productosInput = document.getElementById('productos_seleccionados');
        if (productosInput) {
            productosInput.value = JSON.stringify(productosSeleccionados);
        }
        
        // Actualizar totales
        actualizarTotal();
        
        // Limpiar selección
        limpiarSeleccion(container);

        console.log('Producto agregado exitosamente'); // Debug

    } catch (error) {
        console.error('Error al agregar producto:', error);
        alert('Error al agregar el producto');
    }
}

function limpiarSeleccion(container) {
    try {
        const productoSelect = container.querySelector('.producto-select');
        const colorSelect = container.querySelector('.color-select');
        const talleSelect = container.querySelector('.talle-select');
        const cantidadInput = container.querySelector('.cantidad-input');
        const btnAgregar = container.querySelector('button[onclick="agregarProducto()"]');
        
        if (productoSelect) productoSelect.value = '';
        if (colorSelect) {
            colorSelect.innerHTML = '<option value="">Seleccione color</option>';
        }
        if (talleSelect) {
            talleSelect.innerHTML = '<option value="">Seleccione talle</option>';
        }
        if (cantidadInput) {
            cantidadInput.value = '';
        }
        if (btnAgregar) {
            btnAgregar.disabled = true;
        }
    } catch (error) {
        console.error('Error al limpiar selección:', error);
    }
}

function eliminarProducto(button) {
    const row = button.closest('tr');
    const index = Array.from(row.parentNode.children).indexOf(row);
    
    productosSeleccionados.splice(index, 1);
    row.remove();
    
    // Actualizar el input hidden con los productos
    document.getElementById('productos_seleccionados').value = JSON.stringify(productosSeleccionados);
    
    actualizarTotal();
}

// Validar antes de enviar el formulario
document.querySelector('form').addEventListener('submit', function(e) {
    if (productosSeleccionados.length === 0) {
        e.preventDefault();
        alert('Debe agregar al menos un producto a la venta');
        return false;
    }
});
</script>
@endpush
@endsection 