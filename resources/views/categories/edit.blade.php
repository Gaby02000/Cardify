@extends('welcome')

@section('title', 'Editar Categoría')

@section('content-base')
<div class="flex-1 flex items-center justify-center p-8">
    <div class="w-full max-w-xl bg-[#050f1b] text-a4cadc rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Editar Categoría</h2>

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

        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block mb-1">Nombre de la categoría</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                    class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc" />
            </div>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('categories.show', $category->id) }}"
                class="text-center bg-gray-600 hover:bg-gray-700 text-white py-2 rounded font-semibold transition">
                    Descartar Cambios
                </a> 
                <button type="submit"
                        class="w-full bg-[#163f47] hover:bg-[#1e5d64] text-white py-2 rounded font-semibold transition">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
