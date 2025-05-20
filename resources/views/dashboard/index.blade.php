@extends('welcome')

@section('content-base')
<div class="p-6 w-full">

    <h1 class="text-3xl font-bold mb-6 text-a4cadc">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#163f47] p-6 rounded shadow text-center text-white">
            <h2 class="text-lg font-semibold">Stock Total de GiftCards</h2>
            <p class="text-4xl font-bold">{{ $totalGiftCardStock }}</p>
        </div>
        <div class="bg-[#163f47] p-6 rounded shadow text-center text-white">
            <h2 class="text-lg font-semibold">Valor Estimado en Stock</h2>
            <p class="text-4xl font-bold">${{ number_format($totalGiftCardStockValue, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-[#163f47] p-6 rounded shadow" style="height: 220px;">
            <h2 class="text-xl font-semibold mb-4 text-white">Órdenes en los últimos 6 meses</h2>
            <div style="height: 150px;">
                <canvas id="ordersChart" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>

        <div class="bg-[#163f47] p-6 rounded shadow" style="height: 220px;">
            <h2 class="text-xl font-semibold mb-4 text-white">Distribución Total</h2>
            <div style="height: 150px;">
                <canvas id="totalsPieChart" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
    </div>

</div>

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
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { labels: { color: 'white' } }
            }
        }
    });

    // Gráfico de torta para distribución total
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
                    labels: {
                        color: 'white',
                        font: { size: 14 }
                    }
                }
            }
        }
    });
</script>
@endsection
