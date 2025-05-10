<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de GiftCard</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="min-h-screen flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-xl bg-[#050f1b] text-a4cadc rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Detalle de GiftCard</h2>

        @if ($giftcard->image)
            <div class="mb-4 text-center">
                <img src="{{ asset($giftcard->image) }}" alt="Imagen de GiftCard" class="mx-auto rounded shadow max-h-60">
            </div>
        @endif

        <div class="space-y-3">
            <div>
                <strong class="block">Título:</strong>
                <p>{{ $giftcard->title }}</p>
            </div>

            <div>
                <strong class="block">Descripción:</strong>
                <p>{{ $giftcard->description }}</p>
            </div>

            <div>
                <strong class="block">Categoría:</strong>
                <p>{{ $giftcard->category->name ?? 'Sin categoría' }}</p>
            </div>

            <div>
                <strong class="block">Monto:</strong>
                <p>${{ number_format($giftcard->amount, 2) }}</p>
            </div>

            <div>
                <strong class="block">Precio:</strong>
                <p>${{ number_format($giftcard->price, 2) }}</p>
            </div>

            <div>
                <strong class="block">Stock:</strong>
                <p>{{ $giftcard->stock }}</p>
            </div>

            <div>
                <strong class="block">Fecha de creación:</strong>
                <p>{{ $giftcard->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('giftcards.edit', $giftcard->id) }}"
               class="text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold transition">
                Editar
            </a>

            <form action="{{ route('giftcards.destroy', $giftcard->id) }}" method="POST"
                  onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta giftcard?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-semibold transition">
                    Eliminar
                </button>
            </form>

            <a href="{{ route('giftcards.index') }}"
               class="text-center bg-gray-600 hover:bg-gray-700 text-white py-2 rounded font-semibold transition">
                Volver al listado
            </a>
        </div>
    </div>

</body>
</html>
