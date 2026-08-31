@extends('welcome')

@section('title', 'Dashboard')

@php
    $money = fn ($n) => '$' . number_format((float) $n, 2, ',', '.');
    $int   = fn ($n) => number_format((float) $n, 0, ',', '.');

    $statusStyle = [
        'pagado'     => 'bg-green-50 text-green-700 border-green-200',
        'pendiente'  => 'bg-amber-50 text-amber-700 border-amber-200',
        'rechazado'  => 'bg-red-50 text-red-700 border-red-200',
        'reembolsado'=> 'bg-gray-50 text-gray-500 border-gray-200',
    ];
    $badge = fn ($s) => $statusStyle[$s] ?? 'bg-gray-50 text-gray-500 border-gray-200';
@endphp

@section('content-base')
<div class="w-full space-y-6">

    <div class="flex items-baseline justify-between flex-wrap gap-2">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
        <span class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('d \d\e F, Y') }}</span>
    </div>

    {{-- ===== KPIs ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wide text-gray-500">Ingresos</span>
                <span class="w-8 h-8 grid place-items-center rounded-md bg-gray-50 text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m5-14H9.5a2.5 2.5 0 0 0 0 5h5a2.5 2.5 0 0 1 0 5H6"/></svg>
                </span>
            </div>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $money($paidRevenue) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $money($salesThisMonth) }} este mes</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wide text-gray-500">Órdenes</span>
                <span class="w-8 h-8 grid place-items-center rounded-md bg-gray-50 text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2m-6 9 2 2 4-4"/></svg>
                </span>
            </div>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $int($totalOrders) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $int($paidOrders) }} pagadas · {{ $int($ordersThisMonth) }} este mes</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wide text-gray-500">Clientes</span>
                <span class="w-8 h-8 grid place-items-center rounded-md bg-gray-50 text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m6-1.13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $int($totalClients) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $int($totalUsers) }} usuarios del panel</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium uppercase tracking-wide text-gray-500">Catálogo</span>
                <span class="w-8 h-8 grid place-items-center rounded-md bg-gray-50 text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7Zm0 4h18"/></svg>
                </span>
            </div>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $int($totalGiftCards) }} tarjetas</p>
            <p class="text-xs text-gray-500 mt-1">{{ $int($totalGiftCardStock) }} en stock · {{ $money($totalGiftCardStockValue) }} en valor</p>
        </div>
    </div>

    {{-- ===== Series temporales ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <h2 class="text-sm font-medium text-gray-500 mb-4">Órdenes · últimos 6 meses</h2>
            <div class="h-56"><canvas id="ordersChart"></canvas></div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <h2 class="text-sm font-medium text-gray-500 mb-4">Ventas · últimos 6 meses</h2>
            <div class="h-56"><canvas id="salesChart"></canvas></div>
        </div>
    </div>

    {{-- ===== Distribuciones ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <h2 class="text-sm font-medium text-gray-500 mb-4">Órdenes por estado</h2>
            @if ($statusCounts->isEmpty())
                <p class="text-sm text-gray-500">Sin órdenes todavía.</p>
            @else
                <div class="h-56"><canvas id="statusChart"></canvas></div>
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-5 lg:col-span-2 flex flex-col">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h2 class="text-sm font-medium text-gray-500">Stock por categoría</h2>
                <select id="categorySelect" class="p-1.5 rounded-md max-w-[12rem] bg-white border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900/10">
                    <option value="all">Todas</option>
                    @foreach ($categoryLabels as $index => $category)
                        <option value="{{ $index }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-grow min-h-[14rem]"><canvas id="categoriesPieChart"></canvas></div>
        </div>
    </div>

    {{-- ===== Últimas órdenes + paneles ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white border border-gray-200 rounded-lg lg:col-span-2 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200">
                <h2 class="text-sm font-medium text-gray-500">Últimas órdenes</h2>
                <a href="{{ route('orders.index') }}" class="text-xs text-gray-500 hover:text-gray-900 transition">Ver todas →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="py-2.5 px-5 border-b border-gray-200">#</th>
                            <th class="py-2.5 px-5 border-b border-gray-200">Cliente</th>
                            <th class="py-2.5 px-5 border-b border-gray-200 text-right">Total</th>
                            <th class="py-2.5 px-5 border-b border-gray-200">Estado</th>
                            <th class="py-2.5 px-5 border-b border-gray-200 text-right">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-2.5 px-5 border-b border-gray-100">{{ $order->id }}</td>
                                <td class="py-2.5 px-5 border-b border-gray-100">{{ $order->user->name ?? 'Sin cliente' }}</td>
                                <td class="py-2.5 px-5 border-b border-gray-100 text-right">{{ $money($order->total_price) }}</td>
                                <td class="py-2.5 px-5 border-b border-gray-100">
                                    <span class="inline-block px-2 py-0.5 rounded-full border text-xs {{ $badge($order->status ?? 'pendiente') }}">
                                        {{ ucfirst($order->status ?? 'pendiente') }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-5 border-b border-gray-100 text-right text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 px-5 text-center text-gray-500">No hay órdenes registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <h2 class="text-sm font-medium text-gray-500 mb-3">Stock bajo</h2>
                @forelse ($lowStock as $gc)
                    <div class="flex items-center justify-between py-1.5 text-sm">
                        <span class="truncate pr-3 text-gray-700">{{ $gc->title }}</span>
                        <span class="shrink-0 font-semibold {{ $gc->stock == 0 ? 'text-red-700' : 'text-amber-700' }}">{{ $gc->stock }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Todo con stock holgado.</p>
                @endforelse
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <h2 class="text-sm font-medium text-gray-500 mb-3">Más vendidas</h2>
                @forelse ($topGiftCards as $gc)
                    @php $max = $topGiftCards->max('units') ?: 1; @endphp
                    <div class="py-1.5">
                        <div class="flex items-center justify-between text-sm">
                            <span class="truncate pr-3 text-gray-700">{{ $gc->title }}</span>
                            <span class="shrink-0 text-gray-500">{{ $int($gc->units) }}</span>
                        </div>
                        <div class="mt-1 h-1.5 rounded-full bg-gray-50 overflow-hidden">
                            <div class="h-full bg-gray-800" style="width: {{ max(6, round($gc->units / $max * 100)) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Sin ventas pagadas todavía.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const __dark = document.documentElement.classList.contains('dark');
    const TXT    = __dark ? '#a4cadc' : '#374151';
    const GRID   = __dark ? '#274550' : '#e5e7eb';
    const SERIES = __dark ? '#2a7d89' : '#334155';
    const CARD   = __dark ? '#050f1b' : '#ffffff';
    Chart.defaults.color = __dark ? '#7c9fad' : '#6b7280';
    Chart.defaults.font.family = "ui-sans-serif, system-ui, sans-serif";
    const LEGEND = { color: TXT, font: { size: 12 }, boxWidth: 12, padding: 10 };
    const money = v => '$' + Number(v).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    new Chart(document.getElementById('ordersChart'), {
        type: 'bar',
        data: { labels: @json($labels), datasets: [{ label: 'Órdenes', data: @json($data), backgroundColor: SERIES, borderRadius: 4, maxBarThickness: 44 }] },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: GRID } }, x: { grid: { display: false } } },
            plugins: { legend: { display: false } }
        }
    });

    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: { labels: @json($salesLabels), datasets: [{
            label: 'Ventas', data: @json($salesData),
            borderColor: SERIES, backgroundColor: __dark ? 'rgba(42,125,137,0.15)' : 'rgba(51,65,85,0.1)',
            fill: true, tension: 0.3, pointRadius: 3,
        }] },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, grid: { color: GRID }, ticks: { callback: v => money(v) } }, x: { grid: { display: false } } },
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => money(c.parsed.y) } } }
        }
    });

    @if (!$statusCounts->isEmpty())
    (function () {
        const raw = @json($statusCounts);
        const palette = { pagado: '#37e39b', pendiente: '#ffcc4d', rechazado: '#ff5470', reembolsado: '#94a3b8' };
        const labels = Object.keys(raw);
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: labels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                datasets: [{ data: labels.map(l => raw[l]), backgroundColor: labels.map(l => palette[l] || '#94a3b8'), borderColor: CARD, borderWidth: 2 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '62%', plugins: { legend: { position: 'bottom', labels: LEGEND } } }
        });
    })();
    @endif

    (function () {
        const catLabels = @json($categoryLabels);
        const catData = @json($categoryData);
        const colors = catLabels.map((_, i) => `hsl(200, 22%, ${38 + (i % 5) * 11}%)`);
        const chart = new Chart(document.getElementById('categoriesPieChart'), {
            type: 'doughnut',
            data: { labels: catLabels, datasets: [{ data: catData, backgroundColor: colors, borderColor: CARD, borderWidth: 2 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '55%', plugins: { legend: { position: 'right', labels: LEGEND } } }
        });
        document.getElementById('categorySelect').addEventListener('change', e => {
            const i = e.target.value;
            chart.data.labels = i === 'all' ? catLabels : [catLabels[i]];
            chart.data.datasets[0].data = i === 'all' ? catData : [catData[i]];
            chart.data.datasets[0].backgroundColor = i === 'all' ? colors : [colors[i]];
            chart.update();
        });
    })();
</script>
@endsection
