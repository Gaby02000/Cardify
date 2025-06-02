@extends('welcome')

@section('content-base')
<div class="p-6 w-full">
    <h1 class="text-3xl font-bold mb-6 text-a4cadc">Dashboard</h1>

    {{-- Métricas principales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#163f47] p-6 rounded shadow text-center text-white">
            <h2 class="text-lg font-semibold">Usuarios Registrados</h2>
            <p class="text-4xl font-bold">{{ $totalUsers }}</p>
        </div>
        <div class="bg-[#163f47] p-6 rounded shadow text-center text-white">
            <h2 class="text-lg font-semibold">Tipos de GiftCards</h2>
            <p class="text-4xl font-bold">{{ $totalGiftCards }}</p>
        </div>
        <div class="bg-[#163f47] p-6 rounded shadow text-center text-white">
            <h2 class="text-lg font-semibold">Órdenes Emitidas</h2>
            <p class="text-4xl font-bold">{{ $totalOrders }}</p>
        </div>
        <div class="bg-[#163f47] p-6 rounded shadow text-center text-white">
            <h2 class="text-lg font-semibold">Stock Total de GiftCards</h2>
            <p class="text-4xl font-bold">{{ $totalGiftCardStock }}</p>
        </div>
        <div class="bg-[#163f47] p-6 rounded shadow text-center text-white">
            <h2 class="text-lg font-semibold">Valor Estimado en Stock</h2>
            <p class="text-4xl font-bold">${{ number_format($totalGiftCardStockValue, 2) }}</p>
        </div>
        <div class="bg-[#163f47] p-6 rounded shadow text-center text-white">
            <h2 class="text-lg font-semibold">Ventas Totales</h2>
            <p class="text-4xl font-bold">${{ number_format($totalSales, 2) }}</p>
        </div>
    </div>

    {{-- Gráficos principales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-[#163f47] p-6 rounded shadow" style="height: 260px;">
            <h2 class="text-xl font-semibold mb-4 text-white">Órdenes en los últimos 6 meses</h2>
            <div class="h-full">
                <canvas id="ordersChart" style="width: 100%; height: 180px;"></canvas>
            </div>
        </div>

        <div class="bg-[#163f47] p-6 rounded shadow" style="height: 260px;">
            <h2 class="text-xl font-semibold mb-4 text-white">Ventas en los últimos 6 meses</h2>
            <div class="h-full">
                <canvas id="salesChart" style="width: 100%; height: 180px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Gráficos de distribución --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#163f47] p-6 rounded shadow" style="height: 320px;">
            <h2 class="text-xl font-semibold mb-4 text-white">Distribución Total</h2>
            <div style="height: 220px;">
                <canvas id="totalsPieChart" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>

        <div class="bg-[#163f47] p-6 rounded shadow col-span-1 md:col-span-2 flex flex-col" style="height: 320px;">
            <h2 class="text-xl font-semibold mb-4 text-white">Distribución por Categorías</h2>
            <div class="mb-4">
                <label for="categorySelect" class="text-white font-semibold block">Selecciona una categoría:</label>
                <select id="categorySelect" class="p-2 rounded w-full text-black">
                    <option value="all">Todas</option>
                    @foreach ($categoryLabels as $index => $category)
                        <option value="{{ $index }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-grow">
                <canvas id="categoriesPieChart" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
    /* Responsive: en pantallas menores a 640px limite altura y muestre scroll en dropdown */
    @media (max-width: 640px) {
        #categorySelect {
            max-height: 150px !important;
            overflow-y: auto !important;
            display: block;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de barras para órdenes últimos 6 meses
    const ctxOrders = document.getElementById('ordersChart').getContext('2d');
    const ordersChart = new Chart(ctxOrders, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Órdenes',
                data: @json($data),
                backgroundColor: '#1e5d64',
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { labels: { color: 'white' } } }
        }
    });

    // Gráfico de línea para ventas últimos 6 meses
    const ctxSales = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctxSales, {
        type: 'line',
        data: {
            labels: @json($salesLabels),
            datasets: [{
                label: 'Ventas ($)',
                data: @json($salesData),
                borderColor: '#f6c90e',
                backgroundColor: 'rgba(246, 201, 14, 0.3)',
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { labels: { color: 'white' } } }
        }
    });

    // Gráfico de torta para distribución total (usuarios, stock, órdenes)
    const ctxPie = document.getElementById('totalsPieChart').getContext('2d');
    const totalsPieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Usuarios Registrados', 'Stock de GiftCards', 'Órdenes Emitidas'],
            datasets: [{
                label: 'Totales',
                data: [{{ $totalUsers }}, {{ $totalGiftCardStock }}, {{ $totalOrders }}],
                backgroundColor: ['#4a90e2', '#50e3c2', '#e94e77'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: 'white', font: { size: 14 } }
                }
            }
        }
    });

    // Variables para el gráfico de categorías
    const categoryLabels = @json($categoryLabels);
    const categoryData = @json($categoryData);

    function generateColors(count) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const hue = Math.round((360 * i) / count);
            colors.push(`hsl(${hue}, 70%, 60%)`);
        }
        return colors;
    }

    const categoryColors = generateColors(categoryLabels.length);

    const ctxCategories = document.getElementById('categoriesPieChart').getContext('2d');
    const categoriesPieChart = new Chart(ctxCategories, {
    type: 'pie',
    data: {
        labels: categoryLabels,
        datasets: [{
            label: 'Stock por Categoría',
            data: categoryData,
            backgroundColor: categoryColors,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    color: 'white',
                    font: { size: 14 },
                    boxWidth: 14,
                    padding: 8,
                    maxWidth: 200,
                }
            }
        }
    }
    });

    // Filtrar categorías en el gráfico con dropdown
    document.getElementById('categorySelect').addEventListener('change', e => {
        const selectedIndex = e.target.value;

        if (selectedIndex === 'all') {
            categoriesPieChart.data.labels = categoryLabels;
            categoriesPieChart.data.datasets[0].data = categoryData;
            categoriesPieChart.data.datasets[0].backgroundColor = categoryColors;
        } else {
            categoriesPieChart.data.labels = [categoryLabels[selectedIndex]];
            categoriesPieChart.data.datasets[0].data = [categoryData[selectedIndex]];
            categoriesPieChart.data.datasets[0].backgroundColor = [categoryColors[selectedIndex]];
        }
        categoriesPieChart.update();
    });
</script>

@endsection
