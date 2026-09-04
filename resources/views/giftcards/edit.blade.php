@extends('welcome')

@section('title', 'Editar GiftCard')

@section('content-base')
<div class="flex-1 flex items-center justify-center p-8">
    <div class="w-full max-w-xl bg-white border border-gray-200 rounded-lg p-8 text-gray-700">
        <h2 class="text-xl font-semibold mb-6 text-center text-gray-900">Editar GiftCard</h2>

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

        <form action="{{ route('giftcards.update', $giftcard->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="id_category" class="block mb-1">Categoría</label>
                <select name="id_category" id="id_category" required
                        class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900">
                    <option value="">Seleccionar categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $giftcard->id_category == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="title" class="block mb-1">Título</label>
                <input type="text" name="title" id="title" value="{{ old('title', $giftcard->title) }}" required
                       class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900" />
            </div>

            <div>
                <label for="description" class="block mb-1">Descripción</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900">{{ old('description', $giftcard->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="amount" class="block mb-1">Monto</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount', $giftcard->amount) }}" required
                           class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900" />
                </div>

                <div>
                    <label for="price" class="block mb-1">Precio</label>
                    <input type="number" name="price" id="price" step="0.01" value="{{ old('price', $giftcard->price) }}" required
                           class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900" />
                </div>
            </div>

            <div>
                <label for="image" class="block mb-1">Imagen</label>
                <input type="file" name="image" id="image" accept="image/*" class="text-a4cadc">
            </div>

            <div>
                <label for="stock" class="block mb-1">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $giftcard->stock) }}" required
                       class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900" />
            </div>

            <label class="flex items-start gap-2 text-sm">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $giftcard->is_active) ? 'checked' : '' }}
                       class="mt-0.5 h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900/20">
                <span>
                    Mostrar esta gift card en la página.
                    <span class="text-gray-500">Si lo destildás, se guarda pero los clientes no la ven al comprar.</span>
                </span>
            </label>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
               

                <a href="{{ route('giftcards.show', $giftcard->id) }}"
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
