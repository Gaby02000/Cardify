@extends('welcome')

@section('title', 'Dashboard')

@section('content-base')
<div class="w-full">
    <h1 class="text-2xl font-semibold mb-6 text-gray-900">Dashboard</h1>

    {{-- Métricas principales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-gray-200 p-5 rounded-lg">
            <h2 class="text-sm font-medium text-gray-500">Usuarios Registrados</h2>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-5 rounded-lg">
            <h2 class="text-sm font-medium text-gray-500">Tipos de GiftCards</h2>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ $totalGiftCards }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-5 rounded-lg">
            <h2 class="text-sm font-medium text-gray-500">Órdenes Emitidas</h2>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-5 rounded-lg">
            <h2 class="text-sm font-medium text-gray-500">Stock Total de GiftCards</h2>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ $totalGiftCardStock }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-5 rounded-lg">
            <h2 class="text-sm font-medium text-gray-500">Valor Estimado en Stock</h2>
            <p class="text-3xl font-semibold text-gray-900 mt-1">${{ number_format($totalGiftCardStockValue, 2) }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-5 rounded-lg">
            <h2 class="text-sm font-medium text-gray-500">Ventas Totales</h2>
            <p class="text-3xl font-semibold text-gray-900 mt-1">${{ number_format($totalSales, 2) }}</p>
        </div>
    </div>

    {{-- Gráficos principales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white border border-gray-200 p-5 rounded-lg" style="height: 280px;">
            <h2 class="text-sm font-medium text-gray-500 mb-4">Órdenes en los últimos 6 meses</h2>
            <div style="height: 200px;">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        <div class="bg-white border border-gray-200 p-5 rounded-lg" style="height: 280px;">
            <h2 class="text-sm font-medium text-gray-500 mb-4">Ventas en los últimos 6 meses</h2>
            <div style="height: 200px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Gráficos de distribución --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-gray-200 p-5 rounded-lg" style="height: 340px;">
            <h2 class="text-sm font-medium text-gray-500 mb-4">Distribución Total</h2>
            <div style="height: 250px;">
                <canvas id="totalsPieChart"></canvas>
            </div>
        </div>

        <div class="bg-white border border-gray-200 p-5 rounded-lg col-span-1 md:col-span-2 flex flex-col" style="height: 340px;">
            <h2 class="text-sm font-medium text-gray-500 mb-4">Distribución por Categorías</h2>
            <div class="mb-4">
                <label for="categorySelect" class="text-sm font-medium text-gray-600 block mb-1">Seleccioná una categoría</label>
                <select id="categorySelect" class="p-2 rounded-md w-full max-w-xs bg-white border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900/10">
                    <option value="all">Todas</option>
                    @foreach ($categoryLabels as $index => $category)
                        <option value="{{ $index }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-grow">
                <canvas id="categoriesPieChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.color = '#6b7280';
    Chart.defaults.font.family = "ui-sans-serif, system-ui, sans-serif";
    const LEGEND = { color: '#374151', font: { size: 13 } };
    const GRID = '#e5e7eb';

    const ctxOrders = document.getElementById('ordersChart').getContext('2d');
    new Chart(ctxOrders, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{ label: 'Órdenes', data: @json($data), backgroundColor: '#334155', borderRadius: 4 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, grid: { color: GRID } }, x: { grid: { display: false } } },
            plugins: { legend: { labels: LEGEND } }
        }
    });

    const ctxSales = document.getElementById('salesChart').getContext('2d');
    new Chart(ctxSales, {
        type: 'line',
        data: {
            labels: @json($salesLabels),
            datasets: [{
                label: 'Ventas ($)', data: @json($salesData),
                borderColor: '#334155', backgroundColor: 'rgba(51, 65, 85, 0.1)',
                fill: true, tension: 0.3,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, grid: { color: GRID } }, x: { grid: { display: false } } },
            plugins: { legend: { labels: LEGEND } }
        }
    });

    const ctxPie = document.getElementById('totalsPieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Usuarios Registrados', 'Stock de GiftCards', 'Órdenes Emitidas'],
            datasets: [{
                label: 'Totales',
                data: [{{ $totalUsers }}, {{ $totalGiftCardStock }}, {{ $totalOrders }}],
                backgroundColor: ['#64748b', '#94a3b8', '#cbd5e1'],
                borderWidth: 1, borderColor: '#fff'
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: LEGEND } }
        }
    });

    const categoryLabels = @json($categoryLabels);
    const categoryData = @json($categoryData);

    function generateColors(count) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const light = 45 + (i % 4) * 12;
            colors.push(`hsl(215, 16%, ${light}%)`);
        }
        return colors;
    }

    const categoryColors = generateColors(categoryLabels.length);
    const ctxCategories = document.getElementById('categoriesPieChart').getContext('2d');
    const categoriesPieChart = new Chart(ctxCategories, {
        type: 'pie',
        data: {
            labels: categoryLabels,
            datasets: [{ label: 'Stock por Categoría', data: categoryData, backgroundColor: categoryColors, borderWidth: 1, borderColor: '#fff' }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'right', labels: { ...LEGEND, boxWidth: 14, padding: 8 } } }
        }
    });

    document.getElementById('categorySelect').addEventListener('change', e => {
        const i = e.target.value;
        if (i === 'all') {
            categoriesPieChart.data.labels = categoryLabels;
            categoriesPieChart.data.datasets[0].data = categoryData;
            categoriesPieChart.data.datasets[0].backgroundColor = categoryColors;
        } else {
            categoriesPieChart.data.labels = [categoryLabels[i]];
            categoriesPieChart.data.datasets[0].data = [categoryData[i]];
            categoriesPieChart.data.datasets[0].backgroundColor = [categoryColors[i]];
        }
        categoriesPieChart.update();
    });
</script>
@endsection
