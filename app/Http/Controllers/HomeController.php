<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $comentarios = \App\Models\Comentario::with('user')->latest()->take(5)->get();
    return view('home', compact('comentarios'));
    }
}
