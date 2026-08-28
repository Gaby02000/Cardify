<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cardify - @yield('title', 'Panel')</title>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://img.icons8.com/ios-filled/50/1f2937/bank-card-back-side.png" />
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
    </style>
    @include('partials.theme')
</head>
<body class="min-h-screen flex flex-col" x-data="{ sidebarOpen: true }">

    <!-- Navbar -->
    <nav class="px-6 py-3 flex justify-between items-center bg-white border-b border-gray-200 text-gray-700">
        <div class="flex items-center space-x-4">
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden focus:outline-none text-gray-500 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <a href="{{ route('home') }}" class="text-lg font-semibold text-gray-900 hover:text-gray-700 transition">Cardify</a>
            <span class="hidden sm:inline text-xs text-gray-400">Panel administrativo</span>
        </div>

        <div class="flex items-center space-x-3 relative" x-data="{ open: false }">
            @include('partials.theme-toggle')
            @auth
                <button
                    @click="open = !open"
                    class="flex items-center space-x-2 focus:outline-none text-gray-600 hover:text-gray-900 transition"
                    aria-haspopup="true"
                    :aria-expanded="open.toString()"
                    id="user-menu-button"
                >
                    <img
                        src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=e5e7eb&color=374151' }}"
                        alt="Avatar"
                        class="w-8 h-8 rounded-full object-cover border border-gray-200"
                    />
                    <span class="hidden md:inline-block text-sm font-medium">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div
                    x-show="open"
                    x-cloak
                    @click.outside="open = false"
                    x-transition.origin.top.right
                    class="absolute right-0 top-full mt-2 w-48 rounded-md bg-white shadow-lg border border-gray-200 py-1 z-50 text-sm text-gray-700"
                    role="menu"
                    aria-orientation="vertical"
                    aria-labelledby="user-menu-button"
                >
                    <a href="{{ route('users.show', Auth::user()->id) }}" class="block px-4 py-2 hover:bg-gray-100 transition" role="menuitem">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 transition" role="menuitem">Cerrar sesión</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm hover-link transition">Ingresar</a>
            @endauth
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="flex flex-1 overflow-hidden">

        <!-- Sidebar -->
        <aside x-show="sidebarOpen" x-transition class="sidebar-bg border-r border-gray-200 w-64 p-4 flex-col text-sm text-gray-600 hidden md:flex">
            <nav class="flex flex-col space-y-1">
                <a href="{{ route('dashboard.index') }}" class="px-3 py-2 rounded hover:bg-gray-100 hover:text-gray-900 transition">Dashboard</a>

                <div x-data="{ open: false }" class="flex flex-col">
                    <button @click="open = !open" class="text-left px-3 py-2 rounded hover:bg-gray-100 hover:text-gray-900 transition focus:outline-none flex justify-between items-center">
                        Tarjetas
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-4 flex flex-col">
                        <a href="{{ route('giftcards.index') }}" class="px-3 py-2 rounded hover:bg-gray-100 hover:text-gray-900 transition">Ver tarjetas</a>
                        <a href="{{ route('giftcards.create') }}" class="px-3 py-2 rounded hover:bg-gray-100 hover:text-gray-900 transition">Agregar tarjeta</a>
                    </div>
                </div>

                <div x-data="{ open: false }" class="flex flex-col">
                    <button @click="open = !open" class="text-left px-3 py-2 rounded hover:bg-gray-100 hover:text-gray-900 transition focus:outline-none flex justify-between items-center">
                        Categorías
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-4 flex flex-col">
                        <a href="{{ route('categories.index') }}" class="px-3 py-2 rounded hover:bg-gray-100 hover:text-gray-900 transition">Ver categorías</a>
                        <a href="{{ route('categories.create') }}" class="px-3 py-2 rounded hover:bg-gray-100 hover:text-gray-900 transition">Agregar categoría</a>
                    </div>
                </div>

                <a href="{{ route('orders.index') }}" class="px-3 py-2 rounded hover:bg-gray-100 hover:text-gray-900 transition">Órdenes emitidas</a>
                <a href="{{ route('promotions.index') }}" class="px-3 py-2 rounded hover:bg-gray-100 hover:text-gray-900 transition">Promociones</a>
            </nav>
        </aside>

        <!-- Contenido dinámico -->
        <main class="flex-1 p-6 md:p-8 overflow-auto main-bg">
            @if (session('success'))
                <div class="mb-4 p-3 rounded-md border border-green-200 bg-green-50 text-green-800 text-sm max-w-xl mx-auto text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 rounded-md border border-red-200 bg-red-50 text-red-800 text-sm max-w-xl mx-auto text-center">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content-base')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
