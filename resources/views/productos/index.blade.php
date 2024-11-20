@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Total Productos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <i class="fas fa-box text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Productos</p>
                    <p class="text-lg font-semibold text-gray-700">
                        {{ $totalProductos ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Productos Activos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Productos Activos</p>
                    <p class="text-lg font-semibold text-gray-700">
                        {{ $productosActivos ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Productos Bajo Stock -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Bajo Stock</p>
                    <p class="text-lg font-semibold text-gray-700">
                        {{ $productosBajoStock ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Encabezado y Botones -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Productos</h1>
        <a href="{{ route('productos.create') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            Nuevo Producto
        </a>
    </div>

    <!-- Tabla de Productos -->
    @if(isset($productos) && $productos->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Código
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Marca
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Categoría
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Stock
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Precio
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($productos as $producto)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $producto->codigo }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $producto->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-sm rounded-full bg-blue-100 text-blue-800">
                                    {{ optional($producto->marca)->nombre ?? 'Sin marca' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-sm rounded-full bg-purple-100 text-purple-800">
                                    {{ optional($producto->categoria)->nombre ?? 'Sin categoría' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="@if($producto->stock <= $producto->stock_minimo) text-red-600 font-semibold @endif">
                                    {{ $producto->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${{ number_format($producto->precio_venta, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $producto->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('productos.show', $producto->id_producto) }}" 
                                       class="bg-blue-100 text-blue-700 hover:bg-blue-200 p-2 rounded-lg transition-colors duration-150">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('productos.edit', $producto->id_producto) }}" 
                                       class="bg-yellow-100 text-yellow-700 hover:bg-yellow-200 p-2 rounded-lg transition-colors duration-150">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <button onclick="eliminarProducto({{ $producto->id_producto }})" 
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="mt-4">
            {{ $productos->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500">No hay productos registrados</p>
        </div>
    @endif
</div>
@endsection 

<script>
function eliminarProducto(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Obtener el token CSRF
            const token = document.querySelector('meta[name="csrf-token"]').content;
            
            // Realizar la petición AJAX
            fetch(`/productos/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        '¡Eliminado!',
                        data.message,
                        'success'
                    ).then(() => {
                        // Recargar la página
                        window.location.reload();
                    });
                } else {
                    Swal.fire(
                        'Error',
                        data.message,
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Error',
                    'Hubo un problema al eliminar el producto',
                    'error'
                );
            });
        }
    });
}
</script> 