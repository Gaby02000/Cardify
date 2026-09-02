@extends('welcome')

@section('title', 'Detalle de Categoría')

@section('content-base')
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-xl bg-white border border-gray-200 rounded-lg p-8 text-gray-700">
            <h2 class="text-xl font-semibold mb-6 text-center text-gray-900">Detalle de Categoría</h2>

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
                    <strong class="block">Creada por:</strong>
                    <p>{{ $category->user?->name ?? 'Sin registro' }}</p>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('categories.edit', $category->id) }}"
                   class="text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold transition">
                    Editar
                </a>

                <a href="{{ route('categories.index') }}"
                   class="text-center border border-gray-300 text-gray-700 hover:bg-gray-100 py-2 rounded font-semibold transition">
                    Volver al listado
                </a>

                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" x-data>
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            x-on:click="$dispatch('confirm-delete', { form: $root, message: @js('Se va a borrar la categoría «' . $category->name . '». Esta acción no se puede deshacer.') })"
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-semibold transition">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
