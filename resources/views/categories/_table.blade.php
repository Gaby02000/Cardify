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
                    <a href="{{ route('categories.show', $category->id) }}"
                       class="inline-block border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-100 transition">
                        Ver
                    </a>
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
