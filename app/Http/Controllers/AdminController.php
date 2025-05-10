<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comentario;

class AdminController extends Controller
{
    public function __construct()
    {
        // Asegurarse de que solo los administradores tengan acceso
        $this->middleware('auth');
        $this->middleware('admin');  // Middleware personalizado que verifica que el usuario sea admin
    }

    public function index()
    {
        // Obtener los usuarios, excluyendo los administradores
        $usuarios = User::where('role', '!=', 'admin')->paginate(5, ['*'], 'usuarios_page');

        $comentarios = Comentario::with('user')->paginate(5, ['*'], 'comentarios_page');



        // Pasar los usuarios y comentarios a la vista
        return view('admin', compact('usuarios', 'comentarios'));
    }


    public function destroyUser($id)
    {
        // Buscar el usuario
        $usuario = User::findOrFail($id);

        // Eliminar comentarios y rutas asociadas al usuario
        $usuario->comentarios()->delete();
        $usuario->rutas()->delete();

        // Eliminar el usuario
        $usuario->delete();

        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.index')->with('success', 'Usuario y datos asociados eliminados con éxito.');
    }

    public function destroyComment($id)
    {
        // Buscar el comentario
        $comentario = Comentario::findOrFail($id);

        // Eliminar el comentario
        $comentario->delete();

        // Redirigir con mensaje de éxito
        return redirect()->route('admin.index')->with('success', 'Comentario eliminado con éxito.');
    }
}
