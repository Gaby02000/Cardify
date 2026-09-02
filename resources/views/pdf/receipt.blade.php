@php
    $statusLabels = [
        'pagado' => 'Pagado',
        'completed' => 'Pagado',
        'shipped' => 'Pagado',
        'authorized' => 'Pagado',
        'pendiente' => 'Pendiente',
        'pending' => 'Pendiente',
        'processing' => 'Pendiente',
        'in_process' => 'Pendiente',
        'rechazado' => 'Rechazado',
        'rejected' => 'Rechazado',
        'cancelled' => 'Rechazado',
        'reembolsado' => 'Reembolsado',
        'refunded' => 'Reembolsado',
        'charged_back' => 'Reembolsado',
    ];
    $statusLabel = $statusLabels[$order->status] ?? ucfirst($order->status ?? 'Pendiente');
    $isPaid = in_array($order->status, ['pagado', 'completed', 'shipped', 'authorized'], true);
    $money = fn ($n) => '$' . number_format((float) $n, 2, ',', '.');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: "DejaVu Sans", sans-serif; }
        body { font-size: 12px; color: #1f2937; margin: 0; }
        .wrap { padding: 36px 40px; }

        .top { width: 100%; border-collapse: collapse; margin-bottom: 26px; }
        .top td { vertical-align: top; }
        .brand { font-size: 22px; font-weight: bold; color: #111827; }
        .brand small { display: block; font-size: 11px; font-weight: normal; color: #6b7280; margin-top: 2px; }
        .meta { text-align: right; font-size: 11px; color: #374151; line-height: 1.7; }
        .meta .rec { font-size: 14px; font-weight: bold; color: #111827; }

        .badge {
            display: inline-block; padding: 2px 8px; border-radius: 10px;
            font-size: 10px; font-weight: bold;
            background: #ecfdf5; color: #047857;
        }
        .badge.wait { background: #fffbeb; color: #b45309; }
        .badge.fail { background: #fef2f2; color: #b91c1c; }
        .badge.muted { background: #f3f4f6; color: #6b7280; }

        .client { margin-bottom: 20px; font-size: 11px; color: #374151; line-height: 1.7; }
        .client strong { color: #111827; }

        table.items { width: 100%; border-collapse: collapse; }
        table.items th {
            background: #f9fafb; text-align: left; padding: 8px 10px;
            border-bottom: 2px solid #e5e7eb; font-size: 10px;
            text-transform: uppercase; letter-spacing: .04em; color: #6b7280;
        }
        table.items td { padding: 9px 10px; border-bottom: 1px solid #f0f0f2; }
        .r { text-align: right; }
        .c { text-align: center; }

        .totals { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .totals td { padding: 6px 10px; }
        .totals .lbl { text-align: right; color: #6b7280; }
        .totals .amt { text-align: right; font-weight: bold; font-size: 14px; color: #111827; width: 130px; }

        .note {
            margin-top: 24px; padding: 10px 12px; border-radius: 6px;
            background: #f9fafb; border: 1px solid #e5e7eb;
            font-size: 10px; color: #6b7280;
        }

        .foot { margin-top: 34px; font-size: 10px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
<div class="wrap">
    <table class="top">
        <tr>
            <td>
                <span class="brand">Cardify<small>Regalos digitales al instante</small></span>
            </td>
            <td class="meta">
                <span class="rec">Recibo N.º {{ $number }}</span><br>
                {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}<br>
                <span class="badge {{ $isPaid ? '' : (in_array($order->status, ['rechazado','rejected','cancelled']) ? 'fail' : (in_array($order->status, ['reembolsado','refunded','charged_back']) ? 'muted' : 'wait')) }}">
                    {{ $statusLabel }}
                </span>
            </td>
        </tr>
    </table>

    <div class="client">
        <strong>Cliente:</strong> {{ $order->user->name ?? 'No disponible' }}<br>
        <strong>Correo:</strong> {{ $order->user->email ?? 'No disponible' }}
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Producto</th>
                <th class="c">Cant.</th>
                <th class="r">Precio unit.</th>
                <th class="r">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $item)
                <tr>
                    <td>{{ $item->giftCard->title ?? 'Gift card' }}</td>
                    <td class="c">{{ $item->quantity }}</td>
                    <td class="r">{{ $money($item->price) }}</td>
                    <td class="r">{{ $money($item->price * $item->quantity) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td class="lbl">Total</td>
            <td class="amt">{{ $money($order->total_price) }}</td>
        </tr>
    </table>

    @if ($isPaid)
        <p class="note">
            Los códigos de tus gift cards no se incluyen en este comprobante por seguridad.
            Los encontrás en «Mis compras» dentro de tu cuenta de Cardify.
        </p>
    @endif

    <p class="foot">Este comprobante fue generado automáticamente por Cardify. Conservalo para tus registros.</p>
</div>
</body>
</html>
