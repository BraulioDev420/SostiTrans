<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function show($id)
    {
        // Trae la empresa con sus rutas
        $empresa = Empresa::findOrFail($id);

        // Obtenemos las rutas de la empresa desde la carpeta public/js/{empresa}/
        $directorio = public_path('js/' . strtolower(trim($empresa->nombre)));
        
        if (!is_dir($directorio)) {
            return abort(404, 'No se encontraron rutas para esta empresa');
        }

        
        $archivos = scandir($directorio);
        $rutas = array_filter($archivos, function ($archivo) {
            return str_ends_with($archivo, '.geojson');
        });

        // Eliminar la extensión de los archivos .geojson
        $rutas = array_map(function ($ruta) {
            return pathinfo($ruta, PATHINFO_FILENAME);
        }, $rutas);

        return view('empresa', compact('empresa', 'rutas'));
    }
}
