<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $venta->numero_comprobante }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
        }
        .ticket-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .ticket-info {
            margin: 10px 0;
        }
        .ticket-table {
            width: 100%;
            border-collapse: collapse;
        }
        .ticket-table td {
            padding: 3px 0;
        }
        .ticket-total {
            border-top: 1px dashed #000;
            margin-top: 10px;
            padding-top: 10px;
        }
        @media print {
            body {
                width: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-header">
        <h1 style="font-size: 16px; margin: 0;">Moda y Calzado</h1>
        <p>TICKET DE VENTA</p>
        <p>{{ $venta->numero_comprobante }}</p>
        <p>Fecha: {{ $venta->fecha_venta->format('d/m/Y H:i') }}</p>
    </div>

    @if($venta->cliente)
        <div class="ticket-info">
            <p>Cliente: {{ $venta->cliente->nombre }}</p>
            <p>Doc: {{ $venta->cliente->numero_documento }}</p>
        </div>
    @endif

    <table class="ticket-table">
        <tr>
            <td colspan="4">------------------------</td>
        </tr>
        @foreach($venta->detalles as $detalle)
            <tr>
                <td colspan="4">{{ $detalle->producto->nombre }}</td>
            </tr>
            <tr>
                <td>{{ $detalle->color }}/{{ $detalle->talle }}</td>
                <td>{{ $detalle->cantidad }}x</td>
                <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                <td>${{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4">------------------------</td>
        </tr>
    </table>

    <div class="ticket-total">
        <p>Subtotal: ${{ number_format($venta->subtotal, 2) }}</p>
        <p>IVA: ${{ number_format($venta->iva, 2) }}</p>
        @if($venta->descuento > 0)
            <p>Descuento: ${{ number_format($venta->descuento, 2) }}</p>
        @endif
        <p style="font-size: 14px;"><strong>TOTAL: ${{ number_format($venta->total, 2) }}</strong></p>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <p>¡Gracias por su compra!</p>
        <p>* * * * * * * * * * * *</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Imprimir Ticket
        </button>
        <a href="{{ route('ventas.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">
            Volver
        </a>
    </div>

    <script>
    window.onload = function() {
        // Si es un ticket, imprimir automáticamente
        if ("{{ $venta->tipo_comprobante }}" === "Ticket") {
            window.print();
        }
    }
    </script>
</body>
</html> 