<div class="overflow-x-auto w-full bg-white border border-gray-200 rounded-lg">
    <table class="min-w-full text-sm text-gray-700">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <tr>
                <th class="py-2.5 px-3 border-b border-gray-200 w-[4%]">#</th>
                <th class="py-2.5 px-3 border-b border-gray-200 w-[10%]">Imagen</th>
                <th class="py-2.5 px-3 border-b border-gray-200 w-[16%]">Título</th>
                <th class="py-2.5 px-3 border-b border-gray-200 w-[16%]">Descripción</th>
                <th class="py-2.5 px-3 border-b border-gray-200 w-[15%]">Categoría</th>
                <th class="py-2 px-2 border-b border-gray-200 w-[10%]">
                    <button type="button" data-field="amount"
                            class="sortable inline-flex w-full items-center justify-between gap-1.5 rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-xs font-semibold uppercase tracking-wide text-gray-600 transition hover:border-gray-400 hover:bg-gray-100">
                        <span>Monto</span>
                        <svg class="sort-icon h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4" />
                        </svg>
                    </button>
                </th>
                <th class="py-2 px-2 border-b border-gray-200 w-[10%]">
                    <button type="button" data-field="price"
                            class="sortable inline-flex w-full items-center justify-between gap-1.5 rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-xs font-semibold uppercase tracking-wide text-gray-600 transition hover:border-gray-400 hover:bg-gray-100">
                        <span>Precio</span>
                        <svg class="sort-icon h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4" />
                        </svg>
                    </button>
                </th>
                <th class="py-2 px-2 border-b border-gray-200 w-[7%]">
                    <button type="button" data-field="stock"
                            class="sortable inline-flex w-full items-center justify-between gap-1.5 rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-xs font-semibold uppercase tracking-wide text-gray-600 transition hover:border-gray-400 hover:bg-gray-100">
                        <span>Stock</span>
                        <svg class="sort-icon h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4" />
                        </svg>
                    </button>
                </th>
                <th class="py-2.5 px-3 border-b border-gray-200 w-[12%]">Detalle</th>
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
                    <div class="flex items-center gap-2">
                        <a href="{{ route('giftcards.show', $giftcard->id) }}"
                           class="inline-block border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-100 transition">
                            Ver
                        </a>
                        <form action="{{ route('giftcards.destroy', $giftcard->id) }}" method="POST" x-data>
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    x-on:click="$dispatch('confirm-delete', { form: $root, message: @js('Se va a borrar la gift card «' . $giftcard->title . '». Esta acción no se puede deshacer.') })"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded border border-red-200 text-red-600 transition hover:bg-red-50"
                                    aria-label="Eliminar {{ $giftcard->title }}" title="Eliminar">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 7h12M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-8 0 .8 12.2A2 2 0 0 0 8.79 21h6.42a2 2 0 0 0 1.99-1.8L18 7M10 11v6m4-6v6" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $giftcards->withQueryString()->links('pagination::tailwind') }}
</div>
