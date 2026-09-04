@extends('welcome')

@section('title', 'Promociones')

@section('content-base')
<div class="flex-1 flex items-start justify-center p-8">
    <div class="w-full max-w-lg bg-white border border-gray-200 rounded-lg p-8 text-gray-700">
        <h2 class="text-xl font-semibold text-gray-900">Enviar una notificación</h2>
        <p class="mt-1 text-sm text-gray-500">
            Le llega a los <span class="font-semibold text-gray-700">{{ $subscribers }}</span>
            {{ $subscribers === 1 ? 'dispositivo suscripto' : 'dispositivos suscriptos' }}.
        </p>

        @if ($errors->any())
            <div class="mt-4 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('promotions.send') }}" method="POST" class="mt-5 space-y-4">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" name="title" id="title" required maxlength="80"
                       value="{{ old('title') }}"
                       placeholder="Ofertas nuevas en Cardify"
                       class="mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10" />
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-gray-700">Mensaje</label>
                <textarea name="body" id="body" rows="3" required maxlength="180"
                          placeholder="Entrá y aprovechá los descuentos de esta semana."
                          class="mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10">{{ old('body') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full rounded-md bg-gray-800 px-4 py-2 font-medium text-white transition hover:bg-gray-900">
                Enviar a todos
            </button>
        </form>
    </div>
</div>
@endsection
