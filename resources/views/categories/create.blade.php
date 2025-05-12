@extends('welcome')

@section('title', 'Agregar Categoría')

@section('content-base')
<div class="flex-1 flex items-center justify-center p-8">
    <div class="w-full max-w-xl bg-[#050f1b] text-a4cadc rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Agregar Categoría</h2>

        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-4">
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
                       class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc">
            </div>

            <div class="flex justify-between items-center space-x-4">
                <!-- Volver al menú -->
                <a href="{{ url()->previous() }}" 
                   class="text-center bg-gray-600 hover:bg-gray-700 text-white py-2 rounded font-semibold transition w-full max-w-xs">
                    ← Volver al menú
                </a>

                <!-- Botón Guardar -->
                <button type="submit"
                        class="text-center bg-[#163f47] hover:bg-[#1e5d64] text-white py-2 rounded font-semibold transition w-full max-w-xs">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
