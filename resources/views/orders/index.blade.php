@extends('welcome')

@section('content-base')
<div class="p-6 w-full">
    <h1 class="text-3xl font-bold mb-6 text-a4cadc">Órdenes Emitidas</h1>

    <div class="overflow-x-auto w-full">
        <table class="min-w-full bg-[#050f1b] text-a4cadc border border-gray-600 rounded-lg shadow-sm text-sm">
            <thead class="bg-[#163f47] text-white text-left">
                <tr>
                    <th class="py-2 px-3 border-b border-gray-600 w-[5%]">#</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[20%]">Usuario</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[15%]">Total</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[20%]">Fecha</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[15%]">Estado</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[15%] text-right">Detalle</th> {{-- Nueva columna --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="hover:bg-[#142234] transition-colors">
                        <td class="py-2 px-3 border-b border-gray-700">{{ $order->id }}</td>
                        <td class="py-2 px-3 border-b border-gray-700">{{ $order->user->name ?? 'Usuario no disponible' }}</td>
                        <td class="py-2 px-3 border-b border-gray-700">${{ number_format($order->total_price, 2) }}</td>
                        <td class="py-2 px-3 border-b border-gray-700">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="py-2 px-3 border-b border-gray-700">{{ ucfirst($order->status ?? 'pendiente') }}</td>
                        <td class="py-2 px-3 border-b border-gray-700 text-right">
                            <a href="{{ route('orders.show', $order->id) }}"
                               class="bg-[#1e5d64] text-white px-3 py-1 rounded hover:bg-[#2a7d89] transition">
                                Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No hay órdenes registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
