@extends('welcome') <!-- Si después creamos un layout base -->

@section('content-base')
<div class="p-10">
    <h1 class="text-3xl font-bold mb-6 text-yellow-400">Giftcards disponibles 🎁</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($giftcards as $giftcard)
            <div class="bg-gray-800 p-6 rounded-lg text-center">
                <img src="{{ $giftcard->image_url }}" alt="{{ $giftcard->title }}" class="mx-auto mb-4 h-40 object-contain">
                <h2 class="text-2xl font-bold text-yellow-400 mb-2">{{ $giftcard->title }}</h2>
                <p class="text-gray-300 mb-4">{{ $giftcard->description }}</p>
                <p class="text-yellow-300 font-semibold text-lg mb-4">${{ number_format($giftcard->price, 2) }}</p>
                <a href="{{ route('giftcards.show', $giftcard->id) }}" class="bg-yellow-400 text-black font-bold py-2 px-4 rounded hover:bg-yellow-300">Ver detalles</a>
            </div>
        @endforeach
    </div>
</div>
@endsection
