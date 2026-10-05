<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function create()
    {
        return view('admin.gusuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|in:1,2,3',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        \App\Models\User::create([
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return redirect()->route('admin.usuarios.create')->with('success', 'Usuario registrado correctamente.');
    }
    public function index()
    {
        $usuarios = \App\Models\User::with('role')->get();
        return view('admin.gusuarios.listauser', compact('usuarios'));
    }

    public function update(Request $request, $id)
    {
        $usuario = \App\Models\User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$usuario->id,
            'role_id' => 'required|in:1,2,3',
        ]);

        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = \App\Models\User::findOrFail($id);
        // Avoid deleting the current authenticated user
        if (auth()->id() == $usuario->id) {
            return redirect()->route('admin.usuarios.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
