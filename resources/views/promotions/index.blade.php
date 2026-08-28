@extends('welcome')

@section('title', 'Promociones')

@section('content-base')
<div class="flex-1 flex items-start justify-center p-8">
    <div class="w-full max-w-xl bg-white border border-gray-200 rounded-lg p-8 text-gray-700">
        <h2 class="text-2xl font-bold mb-2 text-center">Enviar promoción</h2>
        <p class="text-center text-sm mb-6 opacity-80">
            Se envía una notificación push a
            <span class="font-bold">{{ $subscribers }}</span>
            {{ $subscribers === 1 ? 'dispositivo suscripto' : 'dispositivos suscriptos' }}.
        </p>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-md mb-4 text-sm">
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
                       class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900" />
            </div>

            <div>
                <label for="body" class="block mb-1">Mensaje</label>
                <textarea name="body" id="body" rows="3" required maxlength="180"
                          placeholder="Solo por hoy. Entrá y aprovechá antes de que se agote."
                          class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900">{{ old('body') }}</textarea>
            </div>

            <div>
                <label for="url" class="block mb-1">Link al hacer click <span class="opacity-60">(opcional)</span></label>
                <input type="text" name="url" id="url"
                       value="{{ old('url', '/') }}"
                       placeholder="/"
                       class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900" />
            </div>

            <button type="submit"
                    class="w-full bg-gray-800 hover:bg-gray-900 text-white transition py-2 rounded-md font-medium">
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
