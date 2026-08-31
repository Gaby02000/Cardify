@extends('welcome')

@section('title', 'Editar Categoría')

@section('content-base')
<div class="flex-1 flex items-center justify-center p-8">
    <div class="w-full max-w-xl bg-white border border-gray-200 rounded-lg p-8 text-gray-700">
        <h2 class="text-xl font-semibold mb-6 text-center text-gray-900">Editar Categoría</h2>

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

        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block mb-1">Nombre de la categoría</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                    class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900" />
            </div>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('categories.show', $category->id) }}"
                class="text-center border border-gray-300 text-gray-700 hover:bg-gray-100 py-2 rounded font-semibold transition">
                    Descartar Cambios
                </a> 
                <button type="submit"
                        class="w-full bg-gray-800 hover:bg-gray-900 text-white py-2 rounded font-semibold transition">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
