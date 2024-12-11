@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Panel de Control</h1>

    <!-- Botón para ver reportes -->
    <div class="mb-4">
        <a href="{{ route('reportes.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Ver Reportes
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Resumen General con diseño mejorado -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
        <!-- Ventas del Día -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Ventas del día</p>
                    <p class="text-2xl font-bold mt-2">{{ $ventasHoy ?? 0 }}</p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm opacity-80">
                <i class="fas fa-clock mr-1"></i> Actualizado hace 5 min
            </div>
        </div>

        <!-- Ingresos -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Ingresos del día</p>
                    <p class="text-2xl font-bold mt-2">${{ number_format($ingresosHoy ?? 0, 2) }}</p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm opacity-80">
                <i class="fas fa-arrow-up mr-1"></i> +5.2% vs ayer
            </div>
        </div>

        <!-- Gastos -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Gastos del día</p>
                    <p class="text-2xl font-bold mt-2">${{ number_format($gastosHoy ?? 0, 2) }}</p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-credit-card text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm opacity-80">
                <i class="fas fa-arrow-down mr-1"></i> -2.1% vs ayer
            </div>
        </div>

        <!-- Balance -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Balance del día</p>
                    <p class="text-2xl font-bold mt-2">${{ number_format($balanceHoy ?? 0, 2) }}</p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm opacity-80">
                <i class="fas fa-balance-scale mr-1"></i> Balance general
            </div>
        </div>
    </div>

    <!-- Gráficos con diseño mejorado -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <!-- Gráfico de Ventas -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Ventas Últimos 7 Días</h2>
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-circle text-blue-500 mr-2"></i> Tendencia
                </div>
            </div>
            <canvas id="ventasChart" height="200"></canvas>
        </div>

        <!-- Gráfico de Gastos -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Gastos por Compras</h2>
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-circle text-red-500 mr-2"></i> Tendencia
                </div>
            </div>
            <canvas id="gastosChart" height="200"></canvas>
        </div>
    </div>

    <!-- Productos Más Vendidos con diseño mejorado -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Productos Más Vendidos Hoy</h2>
            <a href="{{ route('ventas.index', ['fecha_desde' => date('Y-m-d'), 'fecha_hasta' => date('Y-m-d')]) }}" 
               class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-list mr-1"></i> Ver Ventas del Día
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tendencia</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($productosMasVendidosHoy ?? [] as $producto)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-box text-gray-500"></i>
                                </div>
                                {{ $producto->nombre }}
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $producto->cantidad_vendida }}</td>
                        <td class="px-6 py-4">${{ number_format($producto->total_vendido, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="text-green-500"><i class="fas fa-arrow-up"></i> 12%</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay ventas registradas hoy</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Últimas Transacciones con diseño mejorado -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Últimas Ventas -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Últimas Ventas</h2>
                <a href="{{ route('ventas.index') }}" class="text-blue-500 hover:text-blue-700">Ver todas</a>
            </div>
            <div class="space-y-4">
                @forelse($ultimasVentas ?? [] as $venta)
                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-receipt text-blue-500"></i>
                        </div>
                        <div>
                            <p class="font-medium">{{ $venta->cliente->nombre ?? 'Cliente General' }}</p>
                            <p class="text-sm text-gray-500">{{ $venta->tipo_comprobante }} #{{ $venta->numero_comprobante }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium">${{ number_format($venta->total, 2) }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-4 text-center text-gray-500">No hay ventas registradas</div>
                @endforelse
            </div>
        </div>

        <!-- Últimas Compras -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Últimas Compras</h2>
                <a href="{{ route('compras.index') }}" class="text-blue-500 hover:text-blue-700">Ver todas</a>
            </div>
            <div class="space-y-4">
                @forelse($ultimasCompras ?? [] as $compra)
                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-shopping-basket text-green-500"></i>
                        </div>
                        <div>
                            <p class="font-medium">{{ $compra->proveedor->nombre }}</p>
                            <p class="text-sm text-gray-500">{{ $compra->tipo_comprobante }} #{{ $compra->numero_comprobante }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium">${{ number_format($compra->total, 2) }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-4 text-center text-gray-500">No hay compras registradas</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Ventas
    const ctxVentas = document.getElementById('ventasChart').getContext('2d');
    new Chart(ctxVentas, {
        type: 'line',
        data: {
            labels: {!! json_encode($ventasChart['labels'] ?? []) !!},
            datasets: [{
                label: 'Ventas',
                data: {!! json_encode($ventasChart['data'] ?? []) !!},
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de Gastos
    const ctxGastos = document.getElementById('gastosChart').getContext('2d');
    new Chart(ctxGastos, {
        type: 'line',
        data: {
            labels: {!! json_encode($gastosChart['labels'] ?? []) !!},
            datasets: [{
                label: 'Gastos por Compras',
                data: {!! json_encode($gastosChart['data'] ?? []) !!},
                borderColor: 'rgb(239, 68, 68)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
@endsection
