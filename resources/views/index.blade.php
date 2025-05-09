@extends('welcome') <!-- Si después creamos un layout base -->

@section('content-base')
<div class="p-10">
    <h1 class="text-3xl font-bold mb-6 text-yellow-400" style="background-color: #050f1b; color: #a4cadc;">Giftcards disponibles 🎁</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($giftcards as $giftcard)
        <div class="main-bg p-6 rounded-lg text-center text-white border border-gray-600">
            <img src="{{ asset($giftcard->image) }}" alt="{{ $giftcard->title }}" class="mx-auto mb-4 h-40 object-contain">
            <h2 class="text-2xl font-bold text-a4cadc mb-2">{{ $giftcard->title }}</h2>
            <p class="text-a4cadc mb-4">{{ $giftcard->description }}</p>
            <p class="text-a4cadc font-semibold text-lg mb-4">${{ number_format($giftcard->price, 2) }}</p>
            <a href="#" class="sidebar-bg font-bold py-2 px-4 rounded hover:text-a4cadc">Ver detalles</a>
        </div>
        @endforeach
    </div>
</div>
@endsection
