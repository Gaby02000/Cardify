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
    <div class="mb-6 flex flex-wrap items-center gap-4">
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

        <div class="relative">
            <select id="estado"
                class="appearance-none px-3 py-2 pr-10 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
                <option value="">Activas y ocultas</option>
                <option value="activa" @selected(request('estado') === 'activa')>Solo activas</option>
                <option value="oculta" @selected(request('estado') === 'oculta')>Solo ocultas</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <div class="relative">
            <select id="stock_level"
                class="appearance-none px-3 py-2 pr-10 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
                <option value="">Cualquier stock</option>
                <option value="low" @selected(request('stock_level') === 'low')>Poco stock (1–5)</option>
                <option value="out" @selected(request('stock_level') === 'out')>Sin stock</option>
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
    let currentPage = {{ $giftcards->currentPage() }};

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    function fetchGiftcards(page = 1) {
        currentPage = page;

        const params = new URLSearchParams({
            search: document.getElementById('search').value,
            category: document.getElementById('category').value,
            estado: document.getElementById('estado').value,
            stock_level: document.getElementById('stock_level').value,
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

        document.querySelectorAll('.pagination a, nav[aria-label="Navegación de paginación"] a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page');
                fetchGiftcards(page ? Number(page) : 1);
            });
        });

        document.querySelectorAll('.toggle-active').forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.dataset.busy) return;
                btn.dataset.busy = '1';
                btn.style.opacity = '0.5';
                fetch(btn.dataset.url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(() => fetchGiftcards(currentPage))
                .catch(() => {
                    btn.style.opacity = '1';
                    delete btn.dataset.busy;
                    alert('No se pudo cambiar el estado. Probá de nuevo.');
                });
            });
        });
    }

    const SORT_ICON_NEUTRAL = '<path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4" />';
    const SORT_ICON_ASC = '<path stroke-linecap="round" stroke-linejoin="round" d="M6 15l6-6 6 6" />';
    const SORT_ICON_DESC = '<path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />';

    const SORT_BTN_ACTIVE = ['border-gray-900', 'bg-gray-900', 'text-white'];
    const SORT_BTN_IDLE = ['border-gray-300', 'bg-white', 'text-gray-600'];

    function updateSortIcons() {
        document.querySelectorAll('.sortable').forEach(btn => {
            const icon = btn.querySelector('.sort-icon');
            const field = btn.dataset.field;
            const active = field === sortField && sortDirection;

            btn.classList.remove(...SORT_BTN_ACTIVE, ...SORT_BTN_IDLE);
            btn.classList.add(...(active ? SORT_BTN_ACTIVE : SORT_BTN_IDLE));

            icon.classList.toggle('text-white', !!active);
            icon.classList.toggle('text-gray-400', !active);
            icon.innerHTML = active
                ? (sortDirection === 'asc' ? SORT_ICON_ASC : SORT_ICON_DESC)
                : SORT_ICON_NEUTRAL;
        });
    }

    document.getElementById('search').addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fetchGiftcards(), 300);
    });

    document.getElementById('category').addEventListener('change', () => fetchGiftcards());
    document.getElementById('estado').addEventListener('change', () => fetchGiftcards());
    document.getElementById('stock_level').addEventListener('change', () => fetchGiftcards());

    // Inicial
    attachEventListeners();
</script>
@endpush
