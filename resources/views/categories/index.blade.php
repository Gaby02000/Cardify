@extends('welcome')

@section('content-base')
<div class="p-6 w-full">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-semibold text-gray-900">Listado de Categorías</h1>
        <a href="{{ route('categories.create') }}"
           class="inline-flex items-center gap-2 rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-900">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
            </svg>
            Agregar categoría
        </a>
    </div>

    {{-- Formulario de búsqueda --}}
    <div class="mb-6 flex flex-wrap items-center space-x-4">
        <input type="text" id="search" placeholder="Buscar categoría..." 
               class="px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900/10 w-full sm:w-72">
    </div>

    {{-- Contenedor dinámico --}}
    <div id="categories-table">
        @include('categories._table', ['categories' => $categories])
    </div>
</div>
@endsection

@push('scripts')
<script>
    let timeout = null;

    function fetchCategories(page = 1) {
        const search = document.getElementById('search').value;

        const params = new URLSearchParams({
            search,
            page
        });

        fetch(`/categories?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('categories-table').innerHTML = html;
            attachEventListeners();
        });
    }

    function attachEventListeners() {
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page');
                fetchCategories(page);
            });
        });
    }

    document.getElementById('search').addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fetchCategories(), 300);
    });

    attachEventListeners();
</script>
@endpush
