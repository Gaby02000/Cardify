@extends('welcome')

@section('title', 'Órdenes Emitidas')

@section('content-base')
<div class="p-6 w-full">
    <h1 class="text-2xl font-semibold mb-6 text-gray-900">Órdenes Emitidas</h1>

    {{-- Filtros --}}
    <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" id="search" placeholder="Buscar por cliente…"
               value="{{ request('search') }}"
               class="px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900/10">

        <select id="status"
                class="px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
            <option value="">Todos los estados</option>
            @foreach (['pagado' => 'Pagado', 'pendiente' => 'Pendiente', 'rechazado' => 'Rechazado', 'reembolsado' => 'Reembolsado'] as $val => $label)
                <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
            @endforeach
        </select>

        <label class="flex flex-col gap-1 text-xs text-gray-500">
            Desde
            <input type="date" id="date_from" value="{{ request('date_from') }}"
                   class="px-3 py-1.5 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
        </label>

        <label class="flex flex-col gap-1 text-xs text-gray-500">
            Hasta
            <input type="date" id="date_to" value="{{ request('date_to') }}"
                   class="px-3 py-1.5 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
        </label>
    </div>

    <div class="mb-2 flex justify-end">
        <button id="orders-clear" class="text-sm text-gray-500 hover:text-gray-900 transition">Limpiar filtros</button>
    </div>

    {{-- Tabla AJAX --}}
    <div id="orders-table">
        @include('orders._table', ['orders' => $orders])
    </div>
</div>
@endsection

@push('scripts')
<script>
    let ordersTimeout = null;
    let sortField = @json(request('sort')) || null;
    let sortDirection = @json(request('direction')) || null;

    function fetchOrders(page = 1) {
        const params = new URLSearchParams({
            search: document.getElementById('search').value,
            status: document.getElementById('status').value,
            date_from: document.getElementById('date_from').value,
            date_to: document.getElementById('date_to').value,
            sort: sortField ?? '',
            direction: sortDirection ?? '',
            page,
        });

        fetch(`/orders?${params.toString()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => {
                document.getElementById('orders-table').innerHTML = html;
                bindOrdersTable();
                updateSortIcons();
            });
    }

    function bindOrdersTable() {
        document.querySelectorAll('#orders-table .sortable').forEach(header => {
            header.addEventListener('click', () => {
                const field = header.dataset.field;
                if (sortField === field) {
                    sortDirection = sortDirection === 'asc' ? 'desc' : (sortDirection === 'desc' ? null : 'asc');
                    if (sortDirection === null) sortField = null;
                } else {
                    sortField = field;
                    sortDirection = 'asc';
                }
                fetchOrders();
            });
        });

        document.querySelectorAll('#orders-table a[href*="page="]').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const page = new URL(link.href).searchParams.get('page');
                fetchOrders(page);
            });
        });
    }

    function updateSortIcons() {
        document.querySelectorAll('#orders-table .sortable').forEach(header => {
            const icon = header.querySelector('.sort-icon');
            if (!icon) return;
            icon.textContent = header.dataset.field === sortField
                ? (sortDirection === 'asc' ? '↑' : (sortDirection === 'desc' ? '↓' : ''))
                : '';
        });
    }

    ['search'].forEach(id => document.getElementById(id).addEventListener('input', () => {
        clearTimeout(ordersTimeout);
        ordersTimeout = setTimeout(() => fetchOrders(), 300);
    }));
    ['status', 'date_from', 'date_to'].forEach(id =>
        document.getElementById(id).addEventListener('change', () => fetchOrders()));

    document.getElementById('orders-clear').addEventListener('click', () => {
        document.getElementById('search').value = '';
        document.getElementById('status').value = '';
        document.getElementById('date_from').value = '';
        document.getElementById('date_to').value = '';
        sortField = null;
        sortDirection = null;
        fetchOrders();
    });

    bindOrdersTable();
    updateSortIcons();
</script>
@endpush
