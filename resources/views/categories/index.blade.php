@extends('welcome')

@section('content-base')
<div class="p-6 w-full">
    <h1 class="text-3xl font-bold mb-6 text-a4cadc">Listado de Categorías</h1>

    {{-- Formulario de búsqueda --}}
    <form method="GET" action="{{ route('categories.index') }}" class="mb-6 flex flex-wrap items-center space-x-4">
        {{-- Buscador de texto --}}
        <input type="text" name="search" placeholder="Buscar categoría..." 
               value="{{ request('search') }}"
               class="px-4 py-2 rounded bg-[#1e2d3b] text-white placeholder-gray-400 focus:outline-none focus:ring w-1/3">

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
                    <th class="py-3 px-4 border-b border-gray-600 w-[12%]">#</th>  {{-- Mayor altura y más ancho --}}
                    <th class="py-3 px-4 border-b border-gray-600 w-[40%]">Nombre</th>  {{-- Más ancho --}}
                    <th class="py-3 px-4 border-b border-gray-600 w-[40%] text-right">Detalle</th>  {{-- Más ancho y alineación a la derecha --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $index => $category)
                <tr class="hover:bg-[#142234] transition-colors">
                    <td class="py-3 px-4 border-b border-gray-700">{{ $index + 1 }}</td>  {{-- Mayor altura --}}
                    <td class="py-3 px-4 border-b border-gray-700 font-semibold">{{ $category->name }}</td>  {{-- Mayor altura --}}
                    <td class="py-3 px-4 border-b border-gray-700 text-right">  {{-- Alineación a la derecha --}}
                        <a href="{{ route('categories.show', $category->id) }}"
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
