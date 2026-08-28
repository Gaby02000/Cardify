<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cardify - Iniciar sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://img.icons8.com/ios-filled/50/1f2937/bank-card-back-side.png" />
    <style>
        body { background-color: #f4f5f7; color: #374151; font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="bg-white border border-gray-200 rounded-lg p-8 w-full max-w-md">
        <div class="flex items-center justify-center gap-2 mb-1">
            <img src="https://img.icons8.com/ios-filled/50/1f2937/bank-card-back-side.png" alt="" class="w-7 h-7">
            <span class="text-xl font-semibold text-gray-900">Cardify</span>
        </div>
        <p class="text-center text-xs text-gray-400 mb-6">Panel administrativo</p>

        <h2 class="text-lg font-semibold text-center mb-6 text-gray-900">Iniciar sesión</h2>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-md mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                <input type="email" name="email" id="email" required autofocus value="{{ old('email') }}"
                    class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
            </div>

            <button type="submit"
                class="w-full py-2 font-medium rounded-md bg-gray-800 hover:bg-gray-900 text-white transition">
                Ingresar
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-gray-900">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</body>
</html>
