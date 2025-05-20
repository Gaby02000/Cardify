@extends('welcome')

@section('content-base')
<div class="p-6 w-full">
    <h1 class="text-3xl font-bold mb-6 text-a4cadc">Detalle de Orden #{{ $order->id }}</h1>

    <p class="mb-6 text-a4cadc space-y-1">
        <span><strong>Usuario:</strong> {{ $order->user->name ?? 'Usuario no disponible' }}</span><br>
        <span><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</span><br>
        <span><strong>Estado:</strong> {{ ucfirst($order->status ?? 'pendiente') }}</span><br>
        <span><strong>Total:</strong> ${{ number_format($order->total_price, 2) }}</span>
    </p>

    @if($order->orderItems->isEmpty())
        <p class="text-center text-gray-400">No hay items en esta orden.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-[#050f1b] text-a4cadc border border-gray-600 rounded-lg shadow-sm text-sm">
                <thead class="bg-[#163f47] text-white text-left">
                    <tr>
                        <th class="py-3 px-4 border-b border-gray-600">GiftCard</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-center">Cantidad</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-right">Precio Unitario</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr class="hover:bg-[#142234] transition-colors">
                            <td class="py-3 px-4 border-b border-gray-700 font-semibold">{{ $item->giftCard->title ?? 'Giftcard no encontrada' }}</td>
                            <td class="py-3 px-4 border-b border-gray-700 text-center">{{ $item->quantity }}</td>
                            <td class="py-3 px-4 border-b border-gray-700 text-right">${{ number_format($item->price, 2) }}</td>
                            <td class="py-3 px-4 border-b border-gray-700 text-right">${{ number_format($item->quantity * $item->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
