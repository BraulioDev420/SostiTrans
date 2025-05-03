<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasFactory;
class Empresa extends Model
{
    

    protected $fillable = ['nombre'];
    public function rutas()
{
    return $this->hasMany(Ruta::class);
}

}
