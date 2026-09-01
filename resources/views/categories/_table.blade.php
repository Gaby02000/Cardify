<div class="overflow-x-auto w-full bg-white border border-gray-200 rounded-lg">
    <table class="min-w-full text-sm text-gray-700">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <tr>
                <th class="py-2.5 px-4 border-b border-gray-200 w-[12%]">#</th>
                <th class="py-2.5 px-4 border-b border-gray-200 w-[48%]">Nombre</th>
                <th class="py-2.5 px-4 border-b border-gray-200 w-[40%] text-right">Detalle</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $index => $category)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="py-2.5 px-4 border-b border-gray-100">{{ $index + 1 }}</td>
                <td class="py-2.5 px-4 border-b border-gray-100 font-semibold text-gray-900">{{ $category->name }}</td>
                <td class="py-2.5 px-4 border-b border-gray-100 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('categories.show', $category->id) }}"
                           class="inline-block border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-100 transition">
                            Ver
                        </a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" x-data>
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    x-on:click="$dispatch('confirm-delete', { form: $root, message: @js('Se va a borrar la categoría «' . $category->name . '». Esta acción no se puede deshacer.') })"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded border border-red-200 text-red-600 transition hover:bg-red-50"
                                    aria-label="Eliminar {{ $category->name }}" title="Eliminar">
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

{{-- Paginación --}}
<div class="mt-4">
    {{ $categories->withQueryString()->links('pagination::tailwind') }}
</div>
