<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cardify - Página no encontrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://img.icons8.com/ios-filled/50/1f2937/bank-card-back-side.png" />
    <style>
        body { font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; }
    </style>
    @include('partials.theme')
</head>
<body class="flex flex-col min-h-screen">

    <nav class="px-6 py-3 bg-white border-b border-gray-200 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-lg font-semibold text-gray-900 hover:text-gray-700 transition">Cardify</a>
        @include('partials.theme-toggle')
    </nav>

    <main class="flex-1 flex flex-col items-center justify-center text-center p-8">
        <p class="text-6xl font-semibold text-gray-900 mb-2">404</p>
        <p class="text-lg text-gray-600 mb-6">La página que estás buscando no existe.</p>
        <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded-md transition">
            Volver al inicio
        </a>
    </main>
</body>
</html>
