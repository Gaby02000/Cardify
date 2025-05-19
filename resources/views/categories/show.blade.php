@extends('welcome')

@section('title', 'Detalle de Categoría')

@section('content-base')
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-xl bg-[#050f1b] text-a4cadc rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Detalle de Categoría</h2>

            <div class="space-y-3">
                <div>
                    <strong class="block">Nombre:</strong>
                    <p>{{ $category->name }}</p>
                </div>

                <div>
                    <strong class="block">Fecha de creación:</strong>
                    <p>{{ $category->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div>
                    <strong class="block">Creado por:</strong>
                    <p>{{ $category->user->name ?? 'Desconocido' }}</p>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('categories.edit', $category->id) }}"
                   class="text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold transition">
                    Editar
                </a>

                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-semibold transition">
                        Eliminar
                    </button>
                </form>

                <a href="{{ route('categories.index') }}"
                   class="text-center bg-gray-600 hover:bg-gray-700 text-white py-2 rounded font-semibold transition">
                    Volver al listado
                </a>
            </div>
        </div>
    </div>
@endsection
