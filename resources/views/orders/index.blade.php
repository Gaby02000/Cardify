@extends('welcome')

@section('content-base')
<div class="p-6 w-full">
    <h1 class="text-2xl font-semibold mb-6 text-gray-900">Órdenes Emitidas</h1>

    <div class="overflow-x-auto w-full bg-white border border-gray-200 rounded-lg">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="py-2 px-3 border-b border-gray-200 w-[5%]">#</th>
                    <th class="py-2 px-3 border-b border-gray-200 w-[20%]">Usuario</th>
                    <th class="py-2 px-3 border-b border-gray-200 w-[15%]">Total</th>
                    <th class="py-2 px-3 border-b border-gray-200 w-[20%]">Fecha</th>
                    <th class="py-2 px-3 border-b border-gray-200 w-[15%]">Estado</th>
                    <th class="py-2 px-3 border-b border-gray-200 w-[15%] text-right">Detalle</th> {{-- Nueva columna --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-2 px-3 border-b border-gray-100">{{ $order->id }}</td>
                        <td class="py-2 px-3 border-b border-gray-100">{{ $order->user->name ?? 'Usuario no disponible' }}</td>
                        <td class="py-2 px-3 border-b border-gray-100">${{ number_format($order->total_price, 2) }}</td>
                        <td class="py-2 px-3 border-b border-gray-100">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="py-2 px-3 border-b border-gray-100">{{ ucfirst($order->status ?? 'pendiente') }}</td>
                        <td class="py-2 px-3 border-b border-gray-100 text-right">
                            <a href="{{ route('orders.show', $order->id) }}"
                               class="border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-100 transition">
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
