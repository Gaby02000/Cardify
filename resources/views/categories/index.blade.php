@extends('welcome')

@section('content-base')
<div class="p-6 w-full">
    <h1 class="text-3xl font-bold mb-6 text-a4cadc">Listado de Categorías</h1>

    {{-- Formulario de búsqueda --}}
    <div class="mb-6 flex flex-wrap items-center space-x-4">
        <input type="text" id="search" placeholder="Buscar categoría..." 
               class="px-4 py-2 rounded bg-[#1e2d3b] text-white placeholder-gray-400 focus:outline-none focus:ring w-1/3">
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
