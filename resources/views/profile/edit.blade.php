@extends('welcome')

@section('title', 'Editar Perfil')

@section('content-base')
<div class="flex-1 flex items-center justify-center p-8">
    <div class="w-full max-w-xl bg-[#050f1b] text-a4cadc rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Editar Perfil</h2>

        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <strong>Errores:</strong>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', Auth::user()->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Campo de nombre -->
            <div>
                <label for="name" class="block mb-1">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                       class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc" />
            </div>

            <!-- Campo de correo -->
            <div>
                <label for="email" class="block mb-1">Correo electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                       class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc" />
            </div>

            <!-- Campo para la contraseña vieja 
            <div>
                <label for="old_password" class="block mb-1">Contraseña actual</label>
                <input type="password" name="old_password" id="old_password" required
                       class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc" />
            </div>
            -->
            <!-- Campo de nueva contraseña -->
            <div>
                <label for="password" class="block mb-1">Nueva contraseña</label>
                <input type="password" name="password" id="password" placeholder="Nueva contraseña (opcional)"
                       class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc" />
            </div>

            <!-- Confirmar nueva contraseña -->
            <div>
                <label for="password_confirmation" class="block mb-1">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="w-full p-2 rounded bg-[#142234] border border-gray-600 text-a4cadc" />
            </div>

            <button type="submit"
                    class="w-full bg-[#163f47] hover:bg-[#1e5d64] text-white py-2 rounded font-semibold transition">
                Guardar Cambios
            </button>
        </form>
    </div>
</div>
@endsection
