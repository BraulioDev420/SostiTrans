<?php
// app/Http/Controllers/RutaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruta;
use Illuminate\Support\Facades\Storage;

class RutaController extends Controller
{
    public function index()
    {
        // Obtiene todas las rutas con sus paradas
        $rutas = Ruta::with('paradas')->get();
        //dd($rutas);
        \Log::info($rutas);
        // Envía los datos a la vista
        return view('rutas', compact('rutas'));
    }
    
}
