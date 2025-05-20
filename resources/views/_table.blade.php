<div class="overflow-x-auto w-full">
    <table class="min-w-full bg-[#050f1b] text-a4cadc border border-gray-600 rounded-lg shadow-sm text-sm">
        <thead class="bg-[#163f47] text-white text-left">
            <tr>
                <th class="py-2 px-3 border-b border-gray-600 w-[4%]">#</th>
                <th class="py-2 px-3 border-b border-gray-600 w-[10%]">Imagen</th>
                <th class="py-2 px-3 border-b border-gray-600 w-[16%]">Título</th>
                <th class="py-2 px-3 border-b border-gray-600 w-[20%]">Descripción</th>
                <th class="py-2 px-3 border-b border-gray-600 w-[15%]">Categoría</th>
                <th class="sortable py-2 px-3 border-b border-gray-600 w-[10%] cursor-pointer" data-field="price">
                    Precio <span class="sort-icon inline-block ml-1 text-white font-bold text-xs"></span>
                </th>
                <th class="sortable py-2 px-3 border-b border-gray-600 w-[7%] cursor-pointer" data-field="stock">
                    Stock <span class="sort-icon inline-block ml-1 text-white font-bold text-xs"></span>
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

<div class="mt-4 sm:mt-0">
    {{ $giftcards->withQueryString()->links('pagination::tailwind') }}
</div>
