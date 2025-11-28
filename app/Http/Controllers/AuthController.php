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
            
            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                Auth::login($user, $request->filled('remember'));
                $request->session()->regenerate();
                return $this->redirectByRole($user);
            }
        } else {
            // Login normal para otros usuarios
            $user = \App\Models\User::where('email', $email)->first();
            
            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                Auth::login($user, $request->filled('remember'));
                $request->session()->regenerate();
                return $this->redirectByRole($user);
            }
        }

        throw ValidationException::withMessages([
            'email' => ['Las credenciales proporcionadas no son correctas.'],
        ]);
    }


    /**
     * Redirige según el rol del usuario
     */
    private function redirectByRole($user)
    {
        if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        } elseif ($user->isCoordinator()) {
            return redirect()->route('coordinator.dashboard');
        } elseif ($user->isInstructor()) {
            return redirect()->route('instructor.dashboard');
        } else {
            // Admin va al dashboard principal
            return redirect()->intended('/dashboard');
        }
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

