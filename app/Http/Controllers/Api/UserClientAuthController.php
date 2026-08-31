<?php
// app/Http/Controllers/Api/UserClientAuthController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;

class UserClientAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:user_clients,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Log::debug('Validaror: ' . json_encode($validator));

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = UserClient::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Log::debug('User client creado: ' . json_encode($user));

        $cart = Cart::create([
            'user_client_id' => $user->id,
        ]);

        Log::debug('Carrito creado: ' . json_encode($cart));

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }
}
