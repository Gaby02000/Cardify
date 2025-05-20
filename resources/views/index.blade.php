@extends('welcome')

@section('content-base')
<div class="p-6 w-full">
    <h1 class="text-3xl font-bold mb-6 text-a4cadc">Listado de Giftcards</h1>

    {{-- Buscador y filtros --}}
    <div class="mb-6 flex flex-wrap items-center space-x-4">
        <input type="text" id="search" placeholder="Buscar giftcard..." 
               class="px-4 py-2 rounded bg-[#1e2d3b] text-white placeholder-gray-400 focus:outline-none focus:ring w-1/3">

        <div class="relative">
            <select id="category"
                class="appearance-none px-4 py-2 pr-10 rounded bg-[#1e2d3b] text-white focus:outline-none focus:ring">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-white">
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

        fetch(`{{ route('giftcards.index') }}?${params.toString()}`, {
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
