<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cardify - Inicio</title>
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

        .sidebar-bg {
            background-color: #163f47;
        }

        .main-bg {
            background-color: #050f1b;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="px-6 py-4 flex justify-between items-center" style="background-color: #050f1b; color: #a4cadc;">
        <div class="text-2xl font-bold">
            Cardify
        </div>
        <div class="space-x-6">
            <a href="#" class="hover-link transition">Inicio</a>
            <a href="#" class="hover-link transition">Giftcards</a>
            <a href="#" class="hover-link transition">Carrito</a>
            <a href="#" class="hover-link transition">Perfil</a>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="hover-link transition">Cerrar sesión</button>
        </form>
    </nav>

    <div class="flex flex-1">

        <!-- Sidebar -->
        <aside class="sidebar-bg w-64 p-6 flex flex-col text-white">
            <div class="flex items-center mb-10">
                <img src="https://img.icons8.com/ios-filled/100/ffffff/bank-card-back-side.png" alt="Cardify Logo" class="w-10 h-10 mr-3" />
                <span class="text-2xl font-bold">Cardify</span>
            </div>

            <nav class="flex flex-col space-y-4 text-a4cadc">
                <a href="#" class="hover:text-yellow-300">Dashboard</a>
                <a href="#" class="hover:text-yellow-300">Giftcards</a>
                <a href="#" class="hover:text-yellow-300">Mis Compras</a>
                <a href="#" class="hover:text-yellow-300">Configuración</a>
            </nav>
        </aside>

        @yield('content-base')

        <!-- Contenido principal -->
        <main class="flex-1 p-10">
            <h1 class="text-4xl font-extrabold mb-6" style="color: #a4cadc;">Bienvenido a Cardify 🎉</h1>
            <p class="text-lg mb-4">Explora nuestra colección de giftcards exclusivas y haz tu primera compra fácilmente.</p>

            <!-- Botón para agregar GiftCard -->
            <a href="{{ route('giftcards.create') }}" 
            class="inline-block mb-6 px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
                + Agregar GiftCard
            </a>
            <a href="{{ route('categories.create') }}" 
                class="inline-block mb-4 px-4 py-2 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition">
                + Agregar Categoría
            </a>
            <!-- Lugar donde más adelante cargarás las giftcards -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="main-bg rounded p-6 text-center text-white">
                    <p class="font-semibold">Aquí irán las Giftcards 🚀</p>
                </div>
            </div>
        </main>

    </div>

</body>
</html>