<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    public function index()
    {
        $comentarios = Comentario::with('user')->latest()->get();
        $comentarios = Comentario::with('user')->latest()->paginate(5);
        return view('comentarios', compact('comentarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comentario::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->back()->with('comentario_publicado', 'Comentario publicado.');

    }
    public function destacados()
    {
        $comentarios = Comentario::with('user')->latest()->take(5)->get();
        return view('destacados', compact('comentarios'));
    }
    public function destroy($id)
    {
        // Buscar y eliminar el comentario
        $comentario = Comentario::findOrFail($id);
        $comentario->delete();

        // Redirigir con un mensaje
        return redirect()->route('admin.index')->with('success', 'Comentario eliminado exitosamente.');
    }

}
