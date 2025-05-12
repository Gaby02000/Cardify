<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cardify - Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://img.icons8.com/ios-filled/50/000000/bank-card-back-side.png" />
    <style>
        body::before {
            content: "";
            position: fixed;
            top: -10px;
            left: -10px;
            width: calc(100% + 20px);
            height: calc(100% + 20px);
            background-image: 
                radial-gradient(ellipse at center, rgba(0, 0, 0, 0) 40%, rgba(0, 0, 0, 0.7) 100%),
                url('/images/wallpapper-login.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(6px) brightness(0.6);
            z-index: -1;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-center items-center">

    <div class="p-8 rounded-lg shadow-lg w-full max-w-md" style="background-color: #050f1b;">
        <div class="flex items-center justify-center mb-6">
            <img src="https://img.icons8.com/ios-filled/100/ffffff/bank-card-back-side.png" alt="Logo" class="w-12 h-12 mr-3">
            <h1 class="text-3xl font-bold" style="color:#a4cadc;">Cardify</h1>
        </div>

        <h2 class="text-2xl font-semibold text-center mb-6" style="color:#a4cadc;">Crear Cuenta</h2>

        <!-- Formulario de Registro -->
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Nombre completo -->
            <div>
                <label for="name" class="block text-sm mb-1" style="color:#a4cadc;">Nombre completo</label>
                <input type="text" name="name" id="name" required
                    class="w-full px-4 py-2 border border-gray-700 rounded focus:outline-none focus:ring-2"
                    style="background-color: #163f47; color: white;" value="{{ old('name') }}">
                @error('name')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Correo electrónico -->
            <div>
                <label for="email" class="block text-sm mb-1" style="color:#a4cadc;">Correo electrónico</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2 border border-gray-700 rounded focus:outline-none focus:ring-2"
                    style="background-color: #163f47; color: white;" value="{{ old('email') }}">
                @error('email')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm mb-1" style="color:#a4cadc;">Contraseña</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-700 rounded focus:outline-none focus:ring-2"
                    style="background-color: #163f47; color: white;">
                @error('password')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirmar contraseña -->
            <div>
                <label for="password_confirmation" class="block text-sm mb-1" style="color:#a4cadc;">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-700 rounded focus:outline-none focus:ring-2"
                    style="background-color: #163f47; color: white;">
                @error('password_confirmation')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Botón de registro -->
            <button type="submit"
                class="w-full py-2 font-semibold rounded transition"
                style="background-color: #163f47; color: white;"
                onmouseover="this.style.backgroundColor='#050f1b'"
                onmouseout="this.style.backgroundColor='#163f47'">
                Registrarse
            </button>
        </form>

        <!-- Enlace a la página de login -->
        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-sm" style="color: #a4cadc;">¿Ya tenés una cuenta? Iniciá sesión</a>
        </div>

    </div>
</body>
</html>
