<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body      { font-family: DejaVu Sans, sans-serif; font-size:12px; color:#212121; }
        h1        { font-size:18px; margin-bottom:20px; }
        table     { width:100%; border-collapse:collapse; }
        th, td    { border:1px solid #bdbdbd; padding:6px; }
        th        { background:#e0f2f1; text-align:left; }
        .text-r   { text-align:right; }
        .text-c   { text-align:center; }
    </style>
</head>
<body>
    <h1>Factura / Orden #{{ $order->id }}</h1>

    <p>
        <strong>Cliente:</strong> {{ $order->user->name ?? 'Usuario no disponible' }}<br>
        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}<br>
        <strong>Estado:</strong> {{ ucfirst($order->status ?? 'pendiente') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>GiftCard</th>
                <th class="text-c">Cantidad</th>
                <th class="text-r">Unitario</th>
                <th class="text-r">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->giftCard->title ?? 'Giftcard no encontrada' }}</td>
                    <td class="text-c">{{ $item->quantity }}</td>
                    <td class="text-r">${{ number_format($item->price, 2) }}</td>
                    <td class="text-r">${{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-r"><strong>Total</strong></td>
                <td class="text-r"><strong>${{ number_format($order->total_price, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
