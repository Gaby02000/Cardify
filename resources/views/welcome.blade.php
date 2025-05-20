<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cardify - @yield('title', 'Inicio')</title>
    <script src="https://unpkg.com/alpinejs" defer></script>
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
<body class="min-h-screen flex flex-col" x-data="{ sidebarOpen: true }">

    <!-- Navbar -->
    <nav class="px-6 py-4 flex justify-between items-center main-bg text-a4cadc">
        <div class="flex items-center space-x-4">
            <!-- Botón para mostrar/ocultar sidebar -->
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="text-2xl font-bold">Cardify</span>
        </div>
        <div class="space-x-6 hidden md:block">
            <a href="{{ route('home') }}" class="hover-link transition">Inicio</a>
            <a href="{{ route('users.show', Auth::user()->id) }}" class="hover-link transition">Perfil</a>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="inline-flex items-center">
            @csrf
            <button type="submit" class="hover-link transition">Cerrar sesión</button>
        </form>
    </nav>

    <!-- Contenedor principal -->
    <div class="flex flex-1 overflow-hidden">

        <!-- Sidebar -->
        <aside x-show="sidebarOpen" x-transition class="sidebar-bg w-64 p-6 flex flex-col text-white hidden md:flex">
            <a href="{{ route('home') }}" class="flex items-center mb-10 hover:opacity-80 transition">
                <img src="https://img.icons8.com/ios-filled/100/ffffff/bank-card-back-side.png" alt="Cardify Logo" class="w-10 h-10 mr-3" />
                <span class="text-2xl font-bold">Cardify</span>
            </a>

            <nav class="flex flex-col space-y-4 text-a4cadc">
                <a href="{{ route('dashboard.index') }}" class="hover-link transition">Dashboard</a>
                <a href="#">Configuración</a>

                <!-- Tarjetas -->
                <div x-data="{ open: false }" class="flex flex-col space-y-1">
                    <button @click="open = !open" class="text-left hover-link transition focus:outline-none">Tarjetas</button>
                    <div x-show="open" x-transition class="pl-4 flex flex-col space-y-1">
                        <a href="{{ route('giftcards.index') }}" class="hover-link transition">Ver Tarjetas</a>
                        <a href="{{ route('giftcards.create') }}" class="hover-link transition">Agregar Tarjeta</a>
                    </div>
                </div>

                <!-- Categorías -->
                <div x-data="{ open: false }" class="flex flex-col space-y-1">
                    <button @click="open = !open" class="text-left hover-link transition focus:outline-none">Categorías</button>
                    <div x-show="open" x-transition class="pl-4 flex flex-col space-y-1">
                        <a href="{{ route('categories.index') }}" class="hover-link transition">Ver Categorías</a>
                        <a href="{{ route('categories.create') }}" class="hover-link transition">Agregar Categoría</a>
                    </div>
                </div>

                <a href="{{ route('orders.index') }}" class="hover-link transition">Órdenes Emitidas</a>
            </nav>
        </aside>

        <!-- Contenido dinámico -->
        <main class="flex-1 p-8 overflow-auto">
            @yield('content-base')
        </main>
    </div>
@stack('scripts')
</body>
</html>
