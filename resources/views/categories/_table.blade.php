<table class="min-w-full bg-[#050f1b] text-a4cadc border border-gray-600 rounded-lg shadow-sm text-sm">
    <thead class="bg-[#163f47] text-white text-left">
        <tr>
            <th class="py-3 px-4 border-b border-gray-600 w-[12%]">#</th>
            <th class="py-3 px-4 border-b border-gray-600 w-[40%]">Nombre</th>
            <th class="py-3 px-4 border-b border-gray-600 w-[40%] text-right">Detalle</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $index => $category)
        <tr class="hover:bg-[#142234] transition-colors">
            <td class="py-3 px-4 border-b border-gray-700">{{ $index + 1 }}</td>
            <td class="py-3 px-4 border-b border-gray-700 font-semibold">{{ $category->name }}</td>
            <td class="py-3 px-4 border-b border-gray-700 text-right">
                <a href="{{ route('categories.show', $category->id) }}"
                   class="bg-[#1e5d64] text-white px-3 py-1 rounded hover:bg-[#2a7d89] transition">
                    Ver
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Paginación --}}
<div class="mt-4">
    {{ $categories->withQueryString()->links('pagination::tailwind') }}
</div>
