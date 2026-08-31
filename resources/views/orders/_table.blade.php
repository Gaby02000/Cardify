@php
    $statusStyle = [
        'pagado'      => 'bg-green-50 text-green-700 border-green-200',
        'completed'   => 'bg-green-50 text-green-700 border-green-200',
        'shipped'     => 'bg-green-50 text-green-700 border-green-200',
        'pendiente'   => 'bg-amber-50 text-amber-700 border-amber-200',
        'pending'     => 'bg-amber-50 text-amber-700 border-amber-200',
        'processing'  => 'bg-amber-50 text-amber-700 border-amber-200',
        'rechazado'   => 'bg-red-50 text-red-700 border-red-200',
        'cancelled'   => 'bg-red-50 text-red-700 border-red-200',
    ];
    $badge = fn ($s) => $statusStyle[$s] ?? 'bg-gray-50 text-gray-500 border-gray-200';
@endphp

<p class="mb-3 text-sm text-gray-500">
    @if ($orders->total() === 0)
        Sin resultados
    @else
        Mostrando {{ $orders->firstItem() }}–{{ $orders->lastItem() }} de {{ $orders->total() }}
    @endif
</p>

<div class="overflow-x-auto w-full bg-white border border-gray-200 rounded-lg">
    <table class="min-w-full text-sm text-gray-700">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <tr>
                <th class="sortable py-2.5 px-4 border-b border-gray-200 w-[8%] cursor-pointer select-none" data-field="id">
                    # <span class="sort-icon ml-1"></span>
                </th>
                <th class="py-2.5 px-4 border-b border-gray-200 w-[24%]">Cliente</th>
                <th class="sortable py-2.5 px-4 border-b border-gray-200 w-[14%] cursor-pointer select-none text-right" data-field="total_price">
                    Total <span class="sort-icon ml-1"></span>
                </th>
                <th class="sortable py-2.5 px-4 border-b border-gray-200 w-[20%] cursor-pointer select-none" data-field="created_at">
                    Fecha <span class="sort-icon ml-1"></span>
                </th>
                <th class="sortable py-2.5 px-4 border-b border-gray-200 w-[16%] cursor-pointer select-none" data-field="status">
                    Estado <span class="sort-icon ml-1"></span>
                </th>
                <th class="py-2.5 px-4 border-b border-gray-200 w-[10%] text-right">Detalle</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-2.5 px-4 border-b border-gray-100 font-semibold text-gray-900">{{ $order->id }}</td>
                    <td class="py-2.5 px-4 border-b border-gray-100">{{ $order->user->name ?? 'Sin cliente' }}</td>
                    <td class="py-2.5 px-4 border-b border-gray-100 text-right">${{ number_format($order->total_price, 2) }}</td>
                    <td class="py-2.5 px-4 border-b border-gray-100">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                    <td class="py-2.5 px-4 border-b border-gray-100">
                        <span class="inline-block px-2 py-0.5 rounded-full border text-xs {{ $badge($order->status ?? 'pendiente') }}">
                            {{ ucfirst($order->status ?? 'pendiente') }}
                        </span>
                    </td>
                    <td class="py-2.5 px-4 border-b border-gray-100 text-right">
                        <a href="{{ route('orders.show', $order->id) }}"
                           class="inline-block border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-100 transition">
                            Ver
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-6 px-4 text-center text-gray-500">No hay órdenes con esos filtros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $orders->withQueryString()->links('pagination::tailwind') }}
</div>
