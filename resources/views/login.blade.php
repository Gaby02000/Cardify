<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cardify - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://img.icons8.com/ios-filled/50/000000/bank-card-back-side.png" />
    <style>
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/images/fondo-login3.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(6px); /* Cambia este valor si querés más/menos desenfoque */
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

        <h2 class="text-2xl font-semibold text-center mb-6" style="color:#a4cadc;">Iniciar Sesión</h2>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm mb-1" style="color:#a4cadc;">Correo electrónico</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2 border border-gray-700 rounded focus:outline-none focus:ring-2"
                    style="background-color: #163f47; color: white;">
            </div>

            <div>
                <label for="password" class="block text-sm mb-1" style="color:#a4cadc;">Contraseña</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-700 rounded focus:outline-none focus:ring-2"
                    style="background-color: #163f47; color: white;">
            </div>

            <button type="submit"
                class="w-full py-2 font-semibold rounded transition"
                style="background-color: #163f47; color: white;"
                onmouseover="this.style.backgroundColor='#050f1b'"
                onmouseout="this.style.backgroundColor='#163f47'">
                Ingresar
            </button>
        </form>
    </div>
</body>
</html>
