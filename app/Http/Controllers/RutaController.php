<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruta;
use Illuminate\Support\Facades\Auth;

class RutaController extends Controller
{
    // Guarda una ruta en la base de datos
    public function guardar(Request $request)
    {
        $request->validate([
            'nombre_ruta' => 'required|string|max:255',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'bus_sugerido' => 'nullable|string|max:255',
            'geojson_file' => 'required|string|max:255', 
        ]);

        $ruta = Ruta::create([
            'user_id' => Auth::id(),
            'nombre_ruta' => $request->nombre_ruta,
            'origen' => $request->origen,
            'destino' => $request->destino,
            'bus_sugerido' => $request->bus_sugerido,
            'geojson_file' => $request->geojson_file, 
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ruta guardada con éxito.',
            'ruta' => $ruta
        ]);
    }

    // Vista principal
    public function index()
    {
        return view('rutas');
    }
}
