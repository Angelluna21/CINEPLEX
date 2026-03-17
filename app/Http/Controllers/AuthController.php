<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Mostrar el formulario de Login
    public function showLoginForm()
    {
        // Si el usuario ya está logueado, lo mandamos directo al panel
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('login'); // Apunta a tu archivo login.blade.php
    }

    // 2. Procesar las credenciales
    public function login(Request $request)
    {
        // Validamos que envíen correo y contraseña
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentamos iniciar sesión
        if (Auth::attempt($credentials)) {
            // Regeneramos la sesión por seguridad (evita ataques de fijación de sesión)
            $request->session()->regenerate();

            // Redirigimos al panel de administrador
            return redirect()->route('admin.dashboard');
        }

        // Si fallan las credenciales, lo regresamos con un error
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email'); // Mantiene el correo escrito para que no tenga que volver a teclearlo
    }

    // 3. Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Al salir, lo mandamos a la cartelera principal (index)
        return redirect('/'); 
    }
}