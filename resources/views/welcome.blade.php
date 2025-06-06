<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
    <nav class="px-6 py-4 flex justify-between items-center main-bg text-a4cadc shadow">
        <div class="flex items-center space-x-4">
            <!-- Botón para mostrar/ocultar sidebar -->
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden focus:outline-none hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <a href="{{ route('home') }}" class="text-2xl font-bold hover:text-white transition">Cardify</a>
        </div>

        <div class="flex items-center space-x-4 relative" x-data="{ open: false }">
            @auth
                <button 
                    @click="open = !open" 
                    class="flex items-center space-x-2 focus:outline-none hover:text-white transition"
                    aria-haspopup="true" 
                    :aria-expanded="open.toString()"
                    id="user-menu-button"
                >
                    <img 
                        src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=163f47&color=a4cadc' }}" 
                        alt="Avatar" 
                        class="w-8 h-8 rounded-full object-cover"
                    />
                    <span class="hidden md:inline-block font-medium">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4 mt-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div 
                    x-show="open" 
                    x-cloak
                    @click.outside="open = false" 
                    x-transition.origin.top.right 
                    class="absolute right-0 top-full mt-1 w-48 rounded-md shadow-lg py-2 z-50 text-a4cadc border border-gray-700"
                    style="background-color: #163f47;" 
                    role="menu" 
                    aria-orientation="vertical" 
                    aria-labelledby="user-menu-button"
                >
                    <a href="{{ route('users.show', Auth::user()->id) }}" class="block px-4 py-2 hover:bg-[#1f5a63] hover:text-white transition" role="menuitem">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-[#1f5a63] hover:text-white transition" role="menuitem">Cerrar sesión</button>
                    </form>
                </div>

            @else
                <a href="{{ route('login') }}" class="hover-link transition">Ingresar</a>
            @endauth
        </div>
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

                <!-- Tarjetas -->
                <div x-data="{ open: false }" class="flex flex-col space-y-1">
                    <button @click="open = !open" class="text-left hover-link transition focus:outline-none flex justify-between items-center">
                        Tarjetas
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-4 flex flex-col space-y-1">
                        <a href="{{ route('giftcards.index') }}" class="hover-link transition">Ver Tarjetas</a>
                        <a href="{{ route('giftcards.create') }}" class="hover-link transition">Agregar Tarjeta</a>
                    </div>
                </div>

                <!-- Categorías -->
                <div x-data="{ open: false }" class="flex flex-col space-y-1">
                    <button @click="open = !open" class="text-left hover-link transition focus:outline-none flex justify-between items-center">
                        Categorías
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
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
            @if (session('success'))
                <div class="mb-4 p-4 rounded bg-green-600 text-white text-sm text-center max-w-xl mx-auto">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 rounded bg-red-600 text-white text-sm text-center max-w-xl mx-auto">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content-base')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
