<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cardify - Página no encontrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://img.icons8.com/ios-filled/50/000000/bank-card-back-side.png" />
    <style>
        body {
            background-color: #142234;
            color: #a4cadc;
        }

        .hover-link:hover {
            color: #ffffff;
        }

        .main-bg {
            background-color: #050f1b;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="px-6 py-4 flex justify-between items-center main-bg text-a4cadc shadow">
        <a href="{{ route('home') }}" class="text-2xl font-bold hover:text-white transition">Cardify</a>
    </nav>

    <!-- Contenido principal -->
    <main class="flex-1 flex flex-col items-center justify-center text-center p-8">
        <img src="https://img.icons8.com/ios-filled/100/a4cadc/error.png" alt="404" class="mb-6">
        <h1 class="text-6xl font-bold text-red-500 mb-4">404</h1>
        <p class="text-xl mb-6">Lo sentimos, la página que estás buscando no existe.</p>
        <a href="{{ route('home') }}" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            Volver al inicio
        </a>
    </main>
</body>
</html>
