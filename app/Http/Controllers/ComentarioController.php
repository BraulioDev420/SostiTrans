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

        return redirect()->back()->with('success', 'Comentario publicado.');
    }
    public function destacados()
    {
        $comentarios = Comentario::with('user')->latest()->take(5)->get();
        return view('destacados', compact('comentarios'));
    }

}
