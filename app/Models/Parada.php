<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parada extends Model
{
    protected $fillable = ['nombre_parada', 'latitud', 'longitud'];

    // Relación muchos a muchos con Ruta
    public function rutas()
    {
        return $this->belongsToMany(Ruta::class, 'ruta_parada')->withTimestamps();
    }
}
