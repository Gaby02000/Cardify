@extends('welcome')

@section('title', 'Descuentos')

@section('content-base')
<div class="p-6 w-full max-w-5xl mx-auto space-y-8">

    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Descuentos</h1>
        <p class="mt-1 text-sm text-gray-500">
            Poné una o varias tarjetas en promoción, o aplicá el mismo descuento a toda una categoría.
            El precio con descuento se muestra en la tienda y es el que se cobra al pagar.
        </p>
    </div>

    @if ($errors->any())
        <div class="rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ============ Aplicar descuento ============ --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6"
         x-data="{ scope: 'cards', filter: '', selected: [] }">
        <h2 class="text-lg font-semibold text-gray-900">Aplicar descuento</h2>

        <form action="{{ route('discounts.store') }}" method="POST" class="mt-4 space-y-5">
            @csrf
            <input type="hidden" name="scope" :value="scope">

            {{-- Alcance --}}
            <div class="inline-flex rounded-md border border-gray-300 p-1 text-sm">
                <button type="button" x-on:click="scope = 'cards'"
                        :class="scope === 'cards' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100'"
                        class="rounded px-3 py-1.5 font-medium transition">
                    Tarjetas seleccionadas
                </button>
                <button type="button" x-on:click="scope = 'category'"
                        :class="scope === 'category' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100'"
                        class="rounded px-3 py-1.5 font-medium transition">
                    Categoría completa
                </button>
            </div>

            {{-- Porcentaje --}}
            <div>
                <label for="percent" class="block text-sm font-medium text-gray-700">Porcentaje de descuento</label>
                <div class="mt-1 flex items-center gap-2">
                    <input type="number" name="percent" id="percent" min="1" max="95" value="{{ old('percent', 10) }}" required
                           class="w-28 rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
                    <span class="text-sm text-gray-500">% (entre 1 y 95)</span>
                </div>
            </div>

            {{-- Selección por tarjetas --}}
            <div x-show="scope === 'cards'" x-cloak>
                <label class="block text-sm font-medium text-gray-700">Elegí las tarjetas</label>
                <input type="text" x-model="filter" placeholder="Filtrar por título o categoría…"
                       class="mt-1 w-full max-w-sm rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900/10">

                <div class="mt-3 max-h-72 divide-y divide-gray-100 overflow-y-auto rounded-md border border-gray-200">
                    @forelse ($giftcards as $gc)
                        <label class="flex items-center gap-3 px-3 py-2 text-sm hover:bg-gray-50"
                               data-search="{{ Str::lower($gc->title . ' ' . ($gc->category->name ?? '')) }}"
                               x-show="filter === '' || $el.dataset.search.includes(filter.toLowerCase())">
                            <input type="checkbox" name="gift_card_ids[]" value="{{ $gc->id }}"
                                   x-model="selected" :disabled="scope !== 'cards'"
                                   class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900/20">
                            <span class="flex-1">
                                <span class="font-medium text-gray-900">{{ $gc->title }}</span>
                                <span class="text-gray-400">· {{ $gc->category->name ?? 'Sin categoría' }}</span>
                            </span>
                            <span class="text-gray-500">${{ number_format($gc->price, 2) }}</span>
                            @if ($gc->has_discount)
                                <span class="rounded bg-emerald-50 px-1.5 py-0.5 text-xs font-semibold text-emerald-700">
                                    -{{ $gc->discount_percent }}%
                                </span>
                            @endif
                        </label>
                    @empty
                        <p class="px-3 py-4 text-sm text-gray-500">No hay tarjetas cargadas.</p>
                    @endforelse
                </div>

                <p class="mt-2 text-xs text-gray-500">
                    <span x-text="selected.length"></span> seleccionada(s)
                    <button type="button" x-show="selected.length" x-on:click="selected = []"
                            class="ml-2 text-gray-700 underline">limpiar</button>
                </p>
            </div>

            {{-- Selección por categoría --}}
            <div x-show="scope === 'category'" x-cloak>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría</label>
                <select name="category_id" id="category_id" :disabled="scope !== 'category'"
                        class="mt-1 w-full max-w-sm rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
                    <option value="">Elegí una categoría…</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">
                            {{ $cat->name }} ({{ $cat->gift_cards_count }} {{ $cat->gift_cards_count === 1 ? 'tarjeta' : 'tarjetas' }})
                        </option>
                    @endforeach
                </select>
                <p class="mt-2 text-xs text-gray-500">Se aplica el mismo descuento a todas las tarjetas de la categoría.</p>
            </div>

            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-900">
                Aplicar descuento
            </button>
        </form>
    </div>

    {{-- ============ Descuentos activos ============ --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-lg font-semibold text-gray-900">
                Tarjetas con descuento activo
                <span class="ml-1 text-sm font-normal text-gray-400">({{ $active->count() }})</span>
            </h2>

            @if ($active->isNotEmpty())
                <form action="{{ route('discounts.clear') }}" method="POST" x-data>
                    @csrf
                    <input type="hidden" name="all" value="1">
                    <button type="button"
                            x-on:click="$dispatch('confirm-delete', { form: $root, message: @js('Se va a quitar el descuento de todas las tarjetas en promoción.') })"
                            class="rounded-md border border-red-200 px-3 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50">
                        Quitar todos
                    </button>
                </form>
            @endif
        </div>

        @if ($active->isEmpty())
            <p class="mt-4 text-sm text-gray-500">Ninguna tarjeta tiene descuento en este momento.</p>
        @else
            <div class="mt-4 overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="border-b border-gray-200 px-4 py-2.5">Tarjeta</th>
                            <th class="border-b border-gray-200 px-4 py-2.5">Categoría</th>
                            <th class="border-b border-gray-200 px-4 py-2.5 text-right">Precio lista</th>
                            <th class="border-b border-gray-200 px-4 py-2.5 text-right">Descuento</th>
                            <th class="border-b border-gray-200 px-4 py-2.5 text-right">Precio final</th>
                            <th class="border-b border-gray-200 px-4 py-2.5 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($active as $gc)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="border-b border-gray-100 px-4 py-2.5 font-semibold text-gray-900">{{ $gc->title }}</td>
                                <td class="border-b border-gray-100 px-4 py-2.5">{{ $gc->category->name ?? 'Sin categoría' }}</td>
                                <td class="border-b border-gray-100 px-4 py-2.5 text-right text-gray-400 line-through">${{ number_format($gc->price, 2) }}</td>
                                <td class="border-b border-gray-100 px-4 py-2.5 text-right">
                                    <span class="rounded bg-emerald-50 px-1.5 py-0.5 text-xs font-semibold text-emerald-700">-{{ $gc->discount_percent }}%</span>
                                </td>
                                <td class="border-b border-gray-100 px-4 py-2.5 text-right font-semibold text-gray-900">${{ number_format($gc->final_price, 2) }}</td>
                                <td class="border-b border-gray-100 px-4 py-2.5 text-right">
                                    <form action="{{ route('discounts.clear') }}" method="POST" x-data>
                                        @csrf
                                        <input type="hidden" name="gift_card_ids[]" value="{{ $gc->id }}">
                                        <button type="button"
                                                x-on:click="$dispatch('confirm-delete', { form: $root, message: @js('Se va a quitar el descuento de «' . $gc->title . '».') })"
                                                class="rounded border border-red-200 px-2.5 py-1 text-xs font-medium text-red-600 transition hover:bg-red-50">
                                            Quitar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
