@extends('welcome')

@section('content-base')
<div class="p-6 w-full">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-semibold text-gray-900">Listado de Giftcards</h1>
        <a href="{{ route('giftcards.create') }}"
           class="inline-flex items-center gap-2 rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-900">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
            </svg>
            Agregar giftcard
        </a>
    </div>

    {{-- Buscador y filtros --}}
    <div class="mb-6 flex flex-wrap items-center space-x-4">
        <input type="text" id="search" placeholder="Buscar giftcard..." 
               class="px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900/10 w-full sm:w-72">

        <div class="relative">
            <select id="category"
                class="appearance-none px-3 py-2 pr-10 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Tabla AJAX --}}
    <div id="giftcards-table">
        @include('_table', ['giftcards' => $giftcards])
    </div>
</div>
@endsection

@push('scripts')
<script>
    let timeout = null;
    let sortField = null;
    let sortDirection = null; // null = orden original

    function fetchGiftcards(page = 1) {
        const search = document.getElementById('search').value;
        const category = document.getElementById('category').value;

        const params = new URLSearchParams({
            search,
            category,
            sort: sortField ?? '',
            direction: sortDirection ?? '',
            page
        });

        fetch(`/giftcards?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('giftcards-table').innerHTML = html;
            attachEventListeners(); // Re-bind
            updateSortIcons();
        });
    }

    function attachEventListeners() {
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', () => {
                const field = header.dataset.field;
                if (sortField === field) {
                    sortDirection = sortDirection === 'asc' ? 'desc' : (sortDirection === 'desc' ? null : 'asc');
                    if (sortDirection === null) sortField = null; // reset sort
                } else {
                    sortField = field;
                    sortDirection = 'asc';
                }
                fetchGiftcards();
            });
        });

        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page');
                fetchGiftcards(page);
            });
        });
    }

    function updateSortIcons() {
        document.querySelectorAll('.sortable').forEach(header => {
            const icon = header.querySelector('.sort-icon');
            const field = header.dataset.field;
            if (field === sortField) {
                icon.textContent = sortDirection === 'asc' ? '↑' : (sortDirection === 'desc' ? '↓' : '');
            } else {
                icon.textContent = '';
            }
        });
    }

    document.getElementById('search').addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fetchGiftcards(), 300);
    });

    document.getElementById('category').addEventListener('change', fetchGiftcards);

    // Inicial
    attachEventListeners();
</script>
@endpush
