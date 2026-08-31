<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordChanged;

class UserProfileController extends Controller
{
public function show($id)
    {
        $user = User::findOrFail($id);
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:6|confirmed', 
        ]);

        $user = Auth::user(); 

        $user->name = $request->name;
        $user->email = $request->email;

        $passwordChanged = false;

        // Si se llena el campo de nueva contraseña
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password); 
            $passwordChanged = true;
        }

        $user->save(); 

        if ($passwordChanged) {
            Mail::to($user->email)->send(new PasswordChanged($user));
        }

        return redirect()->route('users.show', $user->id)->with('success', 'Perfil actualizado con éxito');
    }
}
