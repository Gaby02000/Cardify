@extends('welcome')

@section('content-base')
<div class="p-6 w-full">
    <h1 class="text-3xl font-bold mb-6 text-a4cadc">Listado de Giftcards</h1>

    {{-- Formulario de búsqueda --}}
    <form method="GET" action="{{ route('giftcards.index') }}" class="mb-6 flex items-center space-x-4">
        <input type="text" name="search" placeholder="Buscar giftcard..." 
               value="{{ request('search') }}"
               class="px-4 py-2 rounded bg-[#1e2d3b] text-white placeholder-gray-400 focus:outline-none focus:ring w-1/3">
        <button type="submit" class="bg-[#1e5d64] text-white px-4 py-2 rounded hover:bg-[#2a7d89] transition">
            Buscar
        </button>
    </form>

    <div class="overflow-x-auto w-full">
        <table class="min-w-full bg-[#050f1b] text-a4cadc border border-gray-600 rounded-lg shadow-sm text-sm">
            <thead class="bg-[#163f47] text-white text-left">
                <tr>
                    <th class="py-2 px-3 border-b border-gray-600 w-[4%]">#</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[10%]">Imagen</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[16%]">Título</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[35%]">Descripción</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[10%]">Precio</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[7%]">Stock</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[8%]">Detalle</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($giftcards as $index => $giftcard)
                <tr class="hover:bg-[#142234] transition-colors">
                    <td class="py-2 px-3 border-b border-gray-700">{{ $index + 1 }}</td>
                    <td class="py-2 px-3 border-b border-gray-700">
                        <img src="{{ $giftcard->image }}" alt="{{ $giftcard->title }}" class="h-12 w-auto max-w-full object-contain rounded">
                    </td>
                    <td class="py-2 px-3 border-b border-gray-700 font-semibold">{{ $giftcard->title }}</td>
                    <td class="py-2 px-3 border-b border-gray-700">{{ Str::limit($giftcard->description, 100) }}</td>
                    <td class="py-2 px-3 border-b border-gray-700">${{ number_format($giftcard->price, 2) }}</td>
                    <td class="py-2 px-3 border-b border-gray-700">{{ $giftcard->stock }}</td>
                    <td class="py-2 px-3 border-b border-gray-700">
                        <a href="{{ route('giftcards.show', $giftcard->id) }}"
                           class="bg-[#1e5d64] text-white px-3 py-1 rounded hover:bg-[#2a7d89] transition">
                            Ver
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
