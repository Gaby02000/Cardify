<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }


    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No se encontró un usuario con ese correo.']);
        }

        $token = Password::broker()->createToken($user);
        Mail::to($user->email)->send(new ResetPasswordMail($token, $user->email));
        return back()->with('status', 'Se envió un correo con el enlace para restablecer la contraseña.');
    }
    public function showResetForm(Request $request, $token)
    {
        return view('reset_password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );
        if ($response == Password::PASSWORD_RESET) {
            // Usando Lang::get para un mensaje traducido
            return redirect()->route('login')->with('status', Lang::get('passwords.reset'));
        } else {
            return back()->withErrors(['email' => 'El token de restablecimiento es inválido o ha expirado.']);
        }
    }
}

           
