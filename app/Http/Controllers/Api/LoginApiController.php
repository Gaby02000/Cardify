<?php
// App\Http\Controllers\Api\LoginApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserClient;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        Log::debug('Credenciales del usuario: ' . json_encode($credentials));
        
        if (Auth::guard('user_client')->attempt($credentials)) {
            $user = Auth::guard('user_client')->user();

            // Si vino con session_id del carrito anónimo
            $sessionId = $request->input('session_id');
            Log::debug('Session ID del usuario: ' . $sessionId);
            if ($sessionId) {
                $guestCart = Cart::where('session_id', $sessionId)->first();
                if ($guestCart) {
                    // Si el usuario ya tenía un carrito propio, mergear ítems
                    $userCart = Cart::firstOrCreate(['user_client_id' => $user->id]);
                    foreach ($guestCart->cartItems as $item) {
                        $existing = $userCart->cartItems()
                            ->where('gift_card_id', $item->gift_card_id)
                            ->first();
                        if ($existing) {
                            $existing->quantity += $item->quantity;
                            $existing->save();
                        } else {
                            $item->cart_id = $userCart->id;
                            $item->save();
                        }
                    }
                    $guestCart->delete();
                }
            }

            return response()->json([
                'message' => 'Login exitoso',
                'user' => $user,
            ]);
        }

        return response()->json(['error' => 'Credenciales inválidas'], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('user_client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout exitoso']);
    }

    public function user(Request $request)
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
    // app/Http/Controllers/Api/LoginApiController.php

}
