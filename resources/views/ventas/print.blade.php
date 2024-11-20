<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Venta #{{ $venta->id_venta }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
            float: right;
            width: 300px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .grand-total {
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 5px;
        }
        @media print {
            body {
                padding: 0;
            }
            button.no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="no-print" style="position: fixed; top: 20px; right: 20px; padding: 10px;">
        Imprimir
    </button>

    <div class="header">
        <h1>Comprobante de Venta</h1>
        <p>{{ config('app.name', 'Laravel') }}</p>
    </div>

    <div class="info-section">
        <div style="float: left;">
            <strong>Cliente:</strong><br>
            @if($venta->id_cliente)
                {{ $venta->cliente->nombre }}<br>
                {{ $venta->cliente->tipo_documento }}: {{ $venta->cliente->numero_documento }}<br>
                @if($venta->cliente->direccion)
                    Dirección: {{ $venta->cliente->direccion }}<br>
                @endif
                @if($venta->cliente->telefono)
                    Teléfono: {{ $venta->cliente->telefono }}
                @endif
            @else
                Venta Libre
            @endif
        </div>
        <div style="float: right;">
            <strong>Comprobante:</strong> {{ $venta->tipo_comprobante }} #{{ $venta->numero_comprobante }}<br>
            <strong>Fecha:</strong> {{ $venta->fecha_venta->format('d/m/Y H:i') }}<br>
            <strong>Estado:</strong> {{ $venta->estado }}<br>
            <strong>Tipo de Pago:</strong> {{ $venta->tipo_pago }}
        </div>
        <div style="clear: both;"></div>
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
            @foreach($venta->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>$ {{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>$ {{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>$ {{ number_format($venta->subtotal, 2) }}</span>
        </div>
        <div class="total-row">
            <span>IVA (21%):</span>
            <span>$ {{ number_format($venta->iva, 2) }}</span>
        </div>
        @if($venta->descuento > 0)
            <div class="total-row">
                <span>Descuento:</span>
                <span>$ {{ number_format($venta->descuento, 2) }}</span>
            </div>
        @endif
        <div class="total-row grand-total">
            <span>Total:</span>
            <span>$ {{ number_format($venta->total, 2) }}</span>
        </div>
    </div>

    <div style="clear: both; margin-top: 40px; text-align: center;">
        <p>¡Gracias por su compra!</p>
    </div>
</body>
</html> 