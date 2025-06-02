@extends('welcome')

@section('title', 'Mi Perfil')

@section('content-base')
<div class="flex-1 flex items-center justify-center p-8">
    <div class="w-full max-w-xl bg-[#050f1b] text-a4cadc rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Mi Perfil</h2>

        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <label class="block mb-1">Nombre</label>
            <p>{{ $user->name }}</p>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Correo electrónico</label>
            <p>{{ $user->email }}</p>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('users.edit', $user->id) }}" class="w-full bg-[#163f47] hover:bg-[#1e5d64] text-white py-2 rounded font-semibold transition text-center">
                Editar Perfil
            </a>
        </div>
    </div>
</div>
@endsection
