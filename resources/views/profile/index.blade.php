@extends('welcome')

@section('title', 'Mi Perfil')

@section('content-base')
<div class="flex-1 flex items-center justify-center p-8">
    <div class="w-full max-w-xl bg-white border border-gray-200 rounded-lg p-8 text-gray-700">
        <h2 class="text-xl font-semibold mb-6 text-center text-gray-900">Mi Perfil</h2>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-md mb-4 text-sm">
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
            <a href="{{ route('users.edit', $user->id) }}" class="w-full bg-gray-800 hover:bg-gray-900 text-white py-2 rounded font-semibold transition text-center">
                Editar Perfil
            </a>
        </div>
    </div>
</div>
@endsection
