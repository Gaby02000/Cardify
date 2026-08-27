@extends('welcome')

@section('title', 'Promociones')

@section('content-base')
<div class="flex-1 flex items-start justify-center p-8">
    <div class="w-full max-w-xl bg-[#050f1b] text-a4cadc rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-2 text-center">Enviar promoción</h2>
        <p class="text-center text-sm mb-6 opacity-80">
            Se envía una notificación push a
            <span class="font-bold">{{ $subscribers }}</span>
            {{ $subscribers === 1 ? 'dispositivo suscripto' : 'dispositivos suscriptos' }}.
        </p>

        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('promotions.send') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="title" class="block mb-1">Título</label>
                <input type="text" name="title" id="title" required maxlength="80"
                       value="{{ old('title') }}"
                       placeholder="¡50% en gift cards de Steam!"
                       class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc" />
            </div>

            <div>
                <label for="body" class="block mb-1">Mensaje</label>
                <textarea name="body" id="body" rows="3" required maxlength="180"
                          placeholder="Solo por hoy. Entrá y aprovechá antes de que se agote."
                          class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc">{{ old('body') }}</textarea>
            </div>

            <div>
                <label for="url" class="block mb-1">Link al hacer click <span class="opacity-60">(opcional)</span></label>
                <input type="text" name="url" id="url"
                       value="{{ old('url', '/') }}"
                       placeholder="/"
                       class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc" />
            </div>

            <button type="submit"
                    class="w-full bg-[#163f47] hover:bg-[#1f5a63] hover:text-white transition py-2 rounded font-semibold">
                Enviar a todos
            </button>
        </form>

        <p class="text-xs opacity-60 mt-6">
            También podés enviarla por consola:
            <code>php artisan push:promo "Título" "Mensaje" --url=/</code>
        </p>
    </div>
</div>
@endsection
