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

        .text-a4cadc {
            color: #a4cadc;
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
            <a href="{{ route('home') }}" class="hover-link transition">Inicio</a>
            <a href="#" class="hover-link transition">Giftcards</a>
            <a href="#" class="hover-link transition">Carrito</a>
            <a href="{{ route('users.show', Auth::user()->id) }}" class="hover-link transition">Perfil</a>
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
                <a href="#">Dashboard</a>
                <a href="#">Giftcards</a>
                <a href="#">Mis Compras</a>
                <a href="#">Configuración</a>
                <!-- Botón para agregar GiftCard -->
                <a href="{{ route('giftcards.create') }}" class="hover-link transition"> Agregar Tarjeta</a>
                <a href="{{ route('categories.create') }}" class="hover-link transition"> Agregar Categoría</a> 
            </nav>
        </aside>

        @yield('content-base')

    </div>

</body>
</html>