<div class="overflow-x-auto w-full bg-white border border-gray-200 rounded-lg">
    <table class="min-w-full text-sm text-gray-700">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <tr>
                <th class="py-2.5 px-3 border-b border-gray-200 w-[4%]">#</th>
                <th class="py-2.5 px-3 border-b border-gray-200 w-[10%]">Imagen</th>
                <th class="py-2.5 px-3 border-b border-gray-200 w-[16%]">Título</th>
                <th class="py-2.5 px-3 border-b border-gray-200 w-[20%]">Descripción</th>
                <th class="py-2.5 px-3 border-b border-gray-200 w-[15%]">Categoría</th>
                <th class="sortable py-2.5 px-3 border-b border-gray-200 w-[10%] cursor-pointer select-none" data-field="amount">
                    Monto <span class="sort-icon inline-block ml-1 text-gray-400 font-bold text-xs"></span>
                </th>
                <th class="sortable py-2.5 px-3 border-b border-gray-200 w-[10%] cursor-pointer select-none" data-field="price">
                    Precio <span class="sort-icon inline-block ml-1 text-gray-400 font-bold text-xs"></span>
                </th>
                <th class="sortable py-2.5 px-3 border-b border-gray-200 w-[7%] cursor-pointer select-none" data-field="stock">
                    Stock <span class="sort-icon inline-block ml-1 text-gray-400 font-bold text-xs"></span>
                </th>
                <th class="py-2.5 px-3 border-b border-gray-200 w-[8%]">Detalle</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($giftcards as $index => $giftcard)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="py-2 px-3 border-b border-gray-100">{{ $index + 1 }}</td>
                <td class="py-2 px-3 border-b border-gray-100">
                    <img src="{{ asset($giftcard->image) }}" alt="{{ $giftcard->title }}" class="h-12 w-auto max-w-full object-contain rounded">
                </td>
                <td class="py-2 px-3 border-b border-gray-100 font-semibold text-gray-900">{{ $giftcard->title }}</td>
                <td class="py-2 px-3 border-b border-gray-100">{{ Str::limit($giftcard->description, 100) }}</td>
                <td class="py-2 px-3 border-b border-gray-100">{{ $giftcard->category->name ?? 'Sin categoría' }}</td>
                <td class="py-2 px-3 border-b border-gray-100">${{ number_format($giftcard->amount, 2) }}</td>
                <td class="py-2 px-3 border-b border-gray-100">${{ number_format($giftcard->price, 2) }}</td>
                <td class="py-2 px-3 border-b border-gray-100">{{ $giftcard->stock }}</td>
                <td class="py-2 px-3 border-b border-gray-100">
                    <a href="{{ route('giftcards.show', $giftcard->id) }}"
                       class="inline-block border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-100 transition">
                        Ver
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $giftcards->withQueryString()->links('pagination::tailwind') }}
</div>
