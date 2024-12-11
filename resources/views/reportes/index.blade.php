@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4" style="color: #333; background-color: #f8f9fa; padding: 10px; border-radius: 5px;">Reportes</h1>
    
    <!-- Botón para volver al dashboard -->
    <div class="text-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg" style="padding: 10px 20px; font-size: 1.2rem; border-radius: 5px;">Volver al inicio</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-dark">
                <div class="card-body text-center">
                    <h5 class="card-title" style="font-size: 1.5rem; font-weight: bold;">Ventas Totales</h5>
                    <h2 class="card-text" style="font-size: 2.5rem; font-weight: bold;">${{ number_format($totalVentas, 2, ',', '.') }} ARS</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-dark">
                <div class="card-body text-center">
                    <h5 class="card-title" style="font-size: 1.5rem; font-weight: bold;">Compras Totales</h5>
                    <h2 class="card-text" style="font-size: 2.5rem; font-weight: bold;">${{ number_format($totalCompras, 2, ',', '.') }} ARS</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-dark">
                <div class="card-body text-center">
                    <h5 class="card-title" style="font-size: 1.5rem; font-weight: bold;">Balance</h5>
                    <h2 class="card-text" style="font-size: 2.5rem; font-weight: bold;">${{ number_format($balance, 2, ',', '.') }} ARS</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <h3 class="mb-3" style="color: #333;"><b>Detalles de Ventas</b></h3>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Venta</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventas as $venta)
                            <tr>
                                <td style="padding: 10px;">{{ $venta->id_venta }}</td>
                                <td style="padding: 10px;">{{ $venta->cliente ? $venta->cliente->nombre : 'Sin Cliente' }}</td>
                                <td style="padding: 10px;">${{ number_format($venta->total, 2, ',', '.') }} ARS</td>
                                <td style="padding: 10px;">{{ $venta->estado }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <h3 class="mb-3" style="color: #333;"><b>Detalles de Compras</b></h3>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID Compra</th>
                                <th>Proveedor</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compras as $compra)
                            <tr>
                                <td style="padding: 10px;">{{ $compra->id_compra }}</td>
                                <td style="padding: 10px;">{{ $compra->proveedor ? $compra->proveedor->razon_social : 'Sin Proveedor' }}</td>
                                <td style="padding: 10px;">${{ number_format($compra->total, 2, ',', '.') }} ARS</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 