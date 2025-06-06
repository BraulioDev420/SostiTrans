<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre_ruta',
        'origen',
        'destino',
        'bus_sugerido',
        'geojson_file',
        'instrucciones_caminando',
        'geojson_caminando'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
