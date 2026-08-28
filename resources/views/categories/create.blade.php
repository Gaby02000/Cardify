@extends('welcome')

@section('title', 'Agregar Categoría')

@section('content-base')
<div class="flex-1 flex items-center justify-center p-8">
    <div class="w-full max-w-xl bg-white border border-gray-200 rounded-lg p-8 text-gray-700">
        <h2 class="text-xl font-semibold mb-6 text-center text-gray-900">Agregar Categoría</h2>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-md mb-4 text-sm">
                <strong>Errores:</strong>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block mb-1">Nombre de la categoría</label>
                <input type="text" name="name" id="name" required
                       class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900">
            </div>

            <div class="flex justify-between items-center space-x-4">
                <!-- Volver al menú -->
                <a href="{{ url()->previous() }}" 
                   class="text-center border border-gray-300 text-gray-700 hover:bg-gray-100 py-2 rounded font-semibold transition w-full max-w-xs">
                    ← Volver al menú
                </a>

                <!-- Botón Guardar -->
                <button type="submit"
                        class="text-center bg-gray-800 hover:bg-gray-900 text-white py-2 rounded font-semibold transition w-full max-w-xs">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
