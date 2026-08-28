@extends('welcome')

@section('title', 'Editar Perfil')

@section('content-base')
<div class="flex-1 flex items-center justify-center p-8">
    <div class="w-full max-w-xl bg-white border border-gray-200 rounded-lg p-8 text-gray-700">
        <h2 class="text-xl font-semibold mb-6 text-center text-gray-900">Editar Perfil</h2>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-md mb-4 text-sm">
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
                       class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900" />
            </div>

            <!-- Campo de correo -->
            <div>
                <label for="email" class="block mb-1">Correo electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                       class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900" />
            </div>

            <!-- Campo para la contraseña vieja 
            <div>
                <label for="old_password" class="block mb-1">Contraseña actual</label>
                <input type="password" name="old_password" id="old_password" required
                       class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900" />
            </div>
            -->
            <!-- Campo de nueva contraseña -->
            <div>
                <label for="password" class="block mb-1">Nueva contraseña</label>
                <input type="password" name="password" id="password" placeholder="Nueva contraseña (opcional)"
                       class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900" />
            </div>

            <!-- Confirmar nueva contraseña -->
            <div>
                <label for="password_confirmation" class="block mb-1">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="w-full p-2 rounded-md bg-white border border-gray-300 text-gray-900" />
            </div>

            <button type="submit"
                    class="w-full bg-gray-800 hover:bg-gray-900 text-white py-2 rounded font-semibold transition">
                Guardar Cambios
            </button>
        </form>
    </div>
</div>
@endsection
