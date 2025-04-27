<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cardify - Inicio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://img.icons8.com/ios-filled/50/000000/bank-card-back-side.png" />
</head>
<body class="bg-black text-yellow-400 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-gray-900 text-yellow-400 px-6 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold">
            Cardify
        </div>
        <div class="space-x-6">
            <a href="#" class="hover:text-yellow-300">Inicio</a>
            <a href="#" class="hover:text-yellow-300">Giftcards</a>
            <a href="#" class="hover:text-yellow-300">Carrito</a>
            <a href="#" class="hover:text-yellow-300">Perfil</a>
        </div>
    </nav>

    <div class="flex flex-1">

        <!-- Sidebar -->
        <aside class="bg-gray-800 w-64 p-6 flex flex-col">
            <div class="flex items-center mb-10">
                <img src="https://img.icons8.com/ios-filled/100/FFD700/bank-card-back-side.png" alt="Cardify Logo" class="w-10 h-10 mr-3" />
                <span class="text-2xl font-bold">Cardify</span>
            </div>

            <nav class="flex flex-col space-y-4">
                <a href="#" class="hover:text-yellow-300">Dashboard</a>
                <a href="#" class="hover:text-yellow-300">Giftcards</a>
                <a href="#" class="hover:text-yellow-300">Mis Compras</a>
                <a href="#" class="hover:text-yellow-300">Configuración</a>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="flex-1 p-10 text-gray-300">
            <h1 class="text-4xl font-extrabold text-yellow-400 mb-6">Bienvenido a Cardify 🎉</h1>
            <p class="text-lg">Explora nuestra colección de giftcards exclusivas y haz tu primera compra fácilmente.</p>

            <!-- Lugar donde más adelante cargarás las giftcards -->
            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Placeholder -->
                <div class="bg-gray-700 rounded p-6 text-center">
                    <p class="text-yellow-400 font-semibold">Aquí irán las Giftcards 🚀</p>
                </div>
            </div>
        </main>

    </div>

</body>
</html>