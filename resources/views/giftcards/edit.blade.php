@extends('welcome')

@section('title', 'Editar GiftCard')

@section('content-base')
<div class="flex-1 flex items-center justify-center p-8">
    <div class="w-full max-w-xl bg-[#050f1b] text-a4cadc rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Editar GiftCard</h2>

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

        <form action="{{ route('giftcards.update', $giftcard->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="id_category" class="block mb-1">Categoría</label>
                <select name="id_category" id="id_category" required
                        class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc">
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
                       class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc" />
            </div>

            <div>
                <label for="description" class="block mb-1">Descripción</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc">{{ old('description', $giftcard->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="amount" class="block mb-1">Monto</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount', $giftcard->amount) }}" required
                           class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc" />
                </div>

                <div>
                    <label for="price" class="block mb-1">Precio</label>
                    <input type="number" name="price" id="price" step="0.01" value="{{ old('price', $giftcard->price) }}" required
                           class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc" />
                </div>
            </div>

            <div>
                <label for="image" class="block mb-1">Imagen</label>
                <input type="file" name="image" id="image" accept="image/*" class="text-a4cadc">
            </div>

            <div>
                <label for="stock" class="block mb-1">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $giftcard->stock) }}" required
                       class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc" />
            </div>

            <button type="submit"
                    class="w-full bg-[#163f47] hover:bg-[#1e5d64] text-white py-2 rounded font-semibold transition">
                Guardar Cambios
            </button>
        </form>
    </div>
</div>
@endsection
