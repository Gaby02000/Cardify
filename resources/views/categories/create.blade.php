<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Agregar Categoría</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #050f1b;
            color: #a4cadc;
        }
        .main-bg {
            background-color: #163f47;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">

    <div class="main-bg p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6 text-white">Agregar Categoría</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-500">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-semibold mb-1">Nombre de la categoría</label>
                <input type="text" name="name" id="name" required
                       class="w-full px-3 py-2 rounded border border-gray-300 text-black">
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ url()->previous() }}" class="text-sm text-blue-300 hover:underline">← Volver</a>
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Guardar
                </button>
            </div>
        </form>
    </div>

</body>
</html>