<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesa el login
     */
    public function login(Request $request)
    {
        // Validación especial: permitir "admin" sin @
        $email = $request->input('email');
        $validationRules = [
            'password' => 'required',
        ];

        // Si es "admin", no validar formato de email
        if ($email === 'admin') {
            $validationRules['email'] = 'required';
        } else {
            $validationRules['email'] = 'required|email';
        }

        $request->validate($validationRules);

        // Si es "admin", buscar directamente por email "admin"
        if ($email === 'admin') {
            $user = \App\Models\User::where('email', 'admin')->first();
            
            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password_hash)) {
                Auth::login($user, $request->filled('remember'));
                $request->session()->regenerate();
                return redirect()->intended('/dashboard');
            }
        } else {
            // Login normal para otros usuarios
            // Usar password_hash en lugar de password
            $user = \App\Models\User::where('email', $email)->first();
            
            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password_hash)) {
                Auth::login($user, $request->filled('remember'));
                $request->session()->regenerate();
                return redirect()->intended('/dashboard');
            }
        }

        throw ValidationException::withMessages([
            'email' => ['Las credenciales proporcionadas no son correctas.'],
        ]);
    }


    /**
     * Cierra la sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

