<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
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
            return back()->withErrors(['email' => 'Este correo no está asociado a ningún usuario.']);
        }

        try {
            $token = Password::broker()->createToken($user);
            Mail::to($user->email)->send(new ResetPasswordMail($token, $user->email));
        } catch (\Throwable $e) {
            Log::error('No se pudo enviar el correo de recuperación: ' . $e->getMessage(), [
                'email' => $user->email,
                'mailer' => config('mail.default'),
            ]);

            return back()->withErrors([
                'email' => 'No se pudo enviar el correo en este momento. Revisá la configuración de correo o probá más tarde.',
            ]);
        }

        return back()->with('status', 'Se envió un correo con el enlace para restablecer la contraseña. Revisá también la carpeta de spam.');
    }
    public function showResetForm(Request $request, $token)
    {
        $email = (string) $request->query('email', '');
        $user = $email !== '' ? User::where('email', $email)->first() : null;

        // Sin este chequeo, el formulario de "nueva contraseña" era accesible
        // con cualquier token/correo (aunque el envío después fallara).
        if (!$user || !Password::broker()->tokenExists($user, $token)) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'El enlace de recuperación es inválido o ya expiró. Pedí uno nuevo.',
            ]);
        }

        return view('reset_password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', Lang::get('passwords.reset'));
        }

        $message = match ($status) {
            Password::INVALID_USER => 'Este correo no está asociado a ningún usuario.',
            Password::RESET_THROTTLED => 'Esperá unos minutos antes de volver a intentarlo.',
            default => 'El enlace de recuperación es inválido o ya expiró.',
        };

        return back()->withErrors(['email' => $message]);
    }
}

           
