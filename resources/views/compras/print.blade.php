<!DOCTYPE html>
<html>
<head>
    <title>Compra #{{ $compra->id_compra }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .details {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Comprobante de Compra</h2>
        <p>Fecha: {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y H:i') }}</p>
    </div>

    <div class="details">
        <p><strong>Proveedor:</strong> {{ $compra->proveedor->razon_social }}</p>
        <p><strong>Comprobante:</strong> {{ $compra->tipo_comprobante }} - {{ $compra->numero_comprobante }}</p>
        <p><strong>Estado:</strong> {{ $compra->estado }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compra->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>Subtotal:</strong> ${{ number_format($compra->subtotal, 2) }}</p>
        <p><strong>IVA:</strong> ${{ number_format($compra->iva, 2) }}</p>
        <p><strong>Total:</strong> ${{ number_format($compra->total, 2) }}</p>
    </div>
</body>
</html> 