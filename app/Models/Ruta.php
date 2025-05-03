<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    // Definir los campos que puedes asignar masivamente
    protected $fillable = ['empresa_id', 'nombre_ruta', 'geojson_file'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    // Relación muchos a muchos con Parada
    public function paradas()
    {
        return $this->belongsToMany(Parada::class, 'ruta_parada')
            ->withPivot('orden')
            ->orderBy('pivot_orden'); // <- ordena las paradas por el campo 'orden'
    }
}
