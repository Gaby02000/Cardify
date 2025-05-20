@extends('welcome')

@section('content-base')
@php
    // Obtener estado actual de orden
    $order = request('order');
    $direction = request('direction');
    
    // Función para calcular el siguiente estado de dirección
    function nextDirection($currentOrder, $col, $currentDir) {
        if ($currentOrder !== $col) return 'asc';
        if ($currentDir === 'asc') return 'desc';
        if ($currentDir === 'desc') return null;
        return 'asc';
    }
@endphp

<div class="p-6 w-full">
    <h1 class="text-3xl font-bold mb-6 text-a4cadc">Listado de Giftcards</h1>

    {{-- Formulario de búsqueda --}}
    <form method="GET" action="{{ route('giftcards.index') }}" class="mb-6 flex flex-wrap items-center space-x-4">
        {{-- Buscador de texto --}}
        <input type="text" name="search" placeholder="Buscar giftcard..." 
               value="{{ request('search') }}"
               class="px-4 py-2 rounded bg-[#1e2d3b] text-white placeholder-gray-400 focus:outline-none focus:ring w-1/3">

        {{-- Filtro por categoría --}}
        <div class="relative">
            <select name="category"
                class="appearance-none px-4 py-2 pr-10 rounded bg-[#1e2d3b] text-white focus:outline-none focus:ring">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            {{-- Ícono SVG personalizado --}}
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        {{-- Botón de búsqueda --}}
        <button type="submit" class="bg-[#1e5d64] text-white px-4 py-2 rounded hover:bg-[#2a7d89] transition">
            Buscar
        </button>
    </form>

    {{-- Tabla de resultados --}}
    <div class="overflow-x-auto w-full">
        <table class="min-w-full bg-[#050f1b] text-a4cadc border border-gray-600 rounded-lg shadow-sm text-sm">
            <thead class="bg-[#163f47] text-white text-left">
                <tr>
                    <th class="py-2 px-3 border-b border-gray-600 w-[4%]">#</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[10%]">Imagen</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[16%]">Título</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[20%]">Descripción</th>
                    <th class="py-2 px-3 border-b border-gray-600 w-[15%]">Categoría</th>
                    
                    {{-- Precio con ordenamiento --}}
                    <th class="py-2 px-3 border-b border-gray-600 w-[10%]">
                        <a href="{{ request()->fullUrlWithQuery(['order' => 'price', 'direction' => nextDirection($order, 'price', $direction)]) }}" class="flex items-center space-x-1 hover:underline">
                            <span>Precio</span>
                            @if ($order === 'price')
                                @if ($direction === 'asc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @elseif ($direction === 'desc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            @endif
                        </a>
                    </th>

                    {{-- Stock con ordenamiento --}}
                    <th class="py-2 px-3 border-b border-gray-600 w-[7%]">
                        <a href="{{ request()->fullUrlWithQuery(['order' => 'stock', 'direction' => nextDirection($order, 'stock', $direction)]) }}" class="flex items-center space-x-1 hover:underline">
                            <span>Stock</span>
                            @if ($order === 'stock')
                                @if ($direction === 'asc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @elseif ($direction === 'desc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            @endif
                        </a>
                    </th>

                    <th class="py-2 px-3 border-b border-gray-600 w-[8%]">Detalle</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($giftcards as $index => $giftcard)
                <tr class="hover:bg-[#142234] transition-colors">
                    <td class="py-2 px-3 border-b border-gray-700">{{ $index + 1 }}</td>
                    <td class="py-2 px-3 border-b border-gray-700">
                        <img src="{{ asset($giftcard->image) }}" alt="{{ $giftcard->title }}" class="h-12 w-auto max-w-full object-contain rounded">
                    </td>
                    <td class="py-2 px-3 border-b border-gray-700 font-semibold">{{ $giftcard->title }}</td>
                    <td class="py-2 px-3 border-b border-gray-700">{{ Str::limit($giftcard->description, 100) }}</td>
                    <td class="py-2 px-3 border-b border-gray-700">{{ $giftcard->category->name ?? 'Sin categoría' }}</td>
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

    {{-- Paginación --}}
    <div class="mt-4 sm:mt-0">
        {{ $giftcards->withQueryString()->links('pagination::tailwind') }}
    </div>
</div>
@endsection
