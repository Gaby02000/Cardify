<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cardify - Resetear Contraseña</title>
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
        .bg-overlay {
            background-color: rgba(0, 0, 0, 0.75);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-center items-center">

    <div class="p-8 rounded-lg shadow-lg w-full max-w-md" style="background-color: #050f1b;">
        <div class="flex items-center justify-center mb-6">
            <img src="https://img.icons8.com/ios-filled/100/ffffff/bank-card-back-side.png" alt="Logo" class="w-12 h-12 mr-3">
            <h1 class="text-3xl font-bold" style="color:#a4cadc;">Cardify</h1>
        </div>

        <h2 class="text-2xl font-semibold text-center mb-6" style="color:#a4cadc;">Restablecer Contraseña</h2>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm mb-1" style="color:#a4cadc;">Correo electrónico</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2 border border-gray-700 rounded focus:outline-none focus:ring-2"
                    style="background-color: #163f47; color: white;" value="{{ $email }}">
            </div>

            <div>
                <label for="password" class="block text-sm mb-1" style="color:#a4cadc;">Nueva Contraseña</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-700 rounded focus:outline-none focus:ring-2"
                    style="background-color: #163f47; color: white;">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm mb-1" style="color:#a4cadc;">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-700 rounded focus:outline-none focus:ring-2"
                    style="background-color: #163f47; color: white;">
            </div>

            <button type="submit"
                class="w-full py-2 font-semibold rounded transition"
                style="background-color: #163f47; color: white;"
                onmouseover="this.style.backgroundColor='#050f1b'"
                onmouseout="this.style.backgroundColor='#163f47'">
                Restablecer Contraseña
            </button>
        </form>
    </div>
</body>
</html>
