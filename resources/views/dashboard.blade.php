@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

    <!-- Resumen General -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Ventas del Día -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Ventas del día</p>
                    <p class="text-lg font-semibold text-gray-700">{{ $ventasHoy ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Ingresos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Ingresos del día</p>
                    <p class="text-lg font-semibold text-green-600">${{ number_format($ingresosHoy ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Gastos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500">
                    <i class="fas fa-credit-card text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Gastos del día</p>
                    <p class="text-lg font-semibold text-red-600">${{ number_format($gastosHoy ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Balance -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Balance del día</p>
                    <p class="text-lg font-semibold {{ ($balanceHoy ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        ${{ number_format($balanceHoy ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y Tablas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Gráfico de Ventas -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ventas Últimos 7 Días</h2>
            <canvas id="ventasChart" height="200"></canvas>
        </div>

        <!-- Gráfico de Gastos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Gastos por Compras Últimos 7 Días</h2>
            <canvas id="gastosChart" height="200"></canvas>
        </div>
    </div>

    <!-- Productos Más Vendidos -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Productos Más Vendidos Hoy</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($productosMasVendidosHoy ?? [] as $producto)
                    <tr>
                        <td class="px-6 py-4">{{ $producto->nombre }}</td>
                        <td class="px-6 py-4">{{ $producto->cantidad_vendida }}</td>
                        <td class="px-6 py-4">${{ number_format($producto->total_vendido, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay ventas registradas hoy</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Últimas Transacciones -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Últimas Ventas -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Últimas Ventas</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comprobante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($ultimasVentas ?? [] as $venta)
                        <tr>
                            <td class="px-6 py-4">{{ $venta->tipo_comprobante }} #{{ $venta->numero_comprobante }}</td>
                            <td class="px-6 py-4">{{ $venta->cliente->nombre ?? 'Cliente General' }}</td>
                            <td class="px-6 py-4">${{ number_format($venta->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay ventas registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Últimas Compras -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Últimas Compras</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comprobante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proveedor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($ultimasCompras ?? [] as $compra)
                        <tr>
                            <td class="px-6 py-4">{{ $compra->tipo_comprobante }} #{{ $compra->numero_comprobante }}</td>
                            <td class="px-6 py-4">{{ $compra->proveedor->nombre ?? 'Proveedor No Especificado' }}</td>
                            <td class="px-6 py-4">${{ number_format($compra->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay compras registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
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
