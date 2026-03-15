<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. CONSULTAR: Muestra la lista de usuarios
    public function index()
    {
        $usuarios = User::all();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    // Muestra el formulario para crear un usuario
    public function create()
    {
        return view('admin.usuarios.create');
    }

    // 2. AGREGAR: Guarda el nuevo usuario en la base de datos
    public function store(Request $request)
    {
        // 1ro: Validamos estrictamente los datos
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'name.regex' => 'El nombre solo puede contener letras y espacios. No se admiten números ni símbolos.',
            'name.min' => 'El nombre debe tener al menos 3 letras.',
            'name.max' => 'El nombre no puede exceder las 50 letras.',
            'email.email' => 'Debes ingresar un formato de correo válido (ejemplo@cineplex.com).'
        ]);

        // 2do: ¡ESTO SE HABÍA BORRADO! Guardamos en la base de datos
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3ro: Redirigimos a la tabla con mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    // Muestra el formulario para editar
    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    // 3. MODIFICAR: Actualiza los datos
    public function update(Request $request, User $usuario)
    {
        // Aplicamos la misma validación estricta para cuando editen a alguien
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
        ], [
            'name.regex' => 'El nombre solo puede contener letras y espacios. No se admiten números ni símbolos.',
            'name.min' => 'El nombre debe tener al menos 3 letras.',
            'name.max' => 'El nombre no puede exceder las 50 letras.',
            'email.email' => 'Debes ingresar un formato de correo válido (ejemplo@cineplex.com).'
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;

        // Solo actualizamos la contraseña si el gerente escribió una nueva
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario modificado exitosamente.');
    }

    // 4. ELIMINAR: Borra al usuario
    public function destroy(User $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}