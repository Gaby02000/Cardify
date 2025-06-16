<?php
// App\Http\Controllers\Api\LoginApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserClient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginApiController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    $user = UserClient::where('email', $credentials['email'])->first();

    if (!$user) {
        return response()->json(['message' => 'Usuario no encontrado'], 401);
    }
    if (!Hash::check($credentials['password'], $user->password)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    $request->session()->put('user_client_id', $user->id);

    return response()->json([
        'message' => 'Login exitoso',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
    ]);
}


    public function logout(Request $request)
    {
        $request->session()->forget('user_client_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout exitoso']);
    }

    public function me(Request $request)
    {
        $userId = $request->session()->get('user_client_id');
        $user = $userId ? UserClient::find($userId) : null;

        if (!$user) {
            return response()->json(['user' => null], 401);
        }

        return response()->json([
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ]
        ]);
    }
}
