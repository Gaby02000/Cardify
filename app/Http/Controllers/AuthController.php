<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Mostrar el formulario de login
    public function showLoginForm()
    {
        return view('login');  // Aquí se hace referencia a la vista login.blade.php
    }
    
    // Procesar el login
    public function login(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->intended('/'); // Redirigir a la página principal o la página deseada
        }

        // Si la autenticación falla, redirigir con un mensaje de error
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

     public function showRegisterForm()
    {
        return view('register');
    }

    // Procesar el registro
    public function register(Request $request)
    {
        // Validar entrada
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Autenticarlo automáticamente
        Auth::login($user);

        // Redirigir a inicio u otra página
        return redirect('/');
    }
}
