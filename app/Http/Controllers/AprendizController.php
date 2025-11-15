<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AprendizController extends Controller
{
    /**
     * Muestra el formulario para registrar aprendices
     */
    public function create()
    {
        // Verificar que el usuario autenticado sea instructor líder
        if (auth()->user()->rol !== 'instructor_lider') {
            abort(403, 'No tienes permisos para registrar aprendices.');
        }

        return view('aprendices.create');
    }

    /**
     * Almacena un nuevo aprendiz
     */
    public function store(Request $request)
    {
        // Verificar que el usuario autenticado sea instructor líder
        if (auth()->user()->rol !== 'instructor_lider') {
            abort(403, 'No tienes permisos para registrar aprendices.');
        }

        $request->validate([
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'numero_documento' => 'required|string|max:20|unique:usuarios,numero_documento',
            'tipo_documento' => 'required|in:CC,CE,TI,PAS',
            'email' => 'required|string|email|max:150|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
        ]);

        $aprendiz = User::create([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'numero_documento' => $request->numero_documento,
            'tipo_documento' => $request->tipo_documento,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'rol' => 'aprendiz',
            'estado' => 'activo',
        ]);

        return redirect()->route('aprendices.index')
            ->with('success', 'Aprendiz registrado exitosamente.');
    }

    /**
     * Lista todos los aprendices
     */
    public function index()
    {
        // Verificar que el usuario autenticado sea instructor líder
        if (auth()->user()->rol !== 'instructor_lider') {
            abort(403, 'No tienes permisos para ver esta página.');
        }

        $aprendices = User::where('rol', 'aprendiz')->paginate(15);

        return view('aprendices.index', compact('aprendices'));
    }
}

