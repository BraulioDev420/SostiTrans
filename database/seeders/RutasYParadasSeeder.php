<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Ruta;
use App\Models\Parada;
use App\Models\Empresa;
class RutasYParadasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        // Asignar paradas a rutas (utilizando los IDs de las paradas)
        $r1 = Ruta::where('nombre_ruta', 'Ruta Norte')->first();
        $r2 = Ruta::where('nombre_ruta', 'Ruta Sur')->first();
        $r3 = Ruta::where('nombre_ruta', 'Ruta Centro')->first();
        $r4 = Ruta::where('nombre_ruta', 'Ruta Este')->first();

        // Asignar paradas a rutas
        $r1->paradas()->attach([1, 2]); // Ruta Norte
        $r2->paradas()->attach([2, 3]); // Ruta Sur
        $r3->paradas()->attach([3, 4]); // Ruta Centro
        $r4->paradas()->attach([1, 4]); // Ruta Este
    }
}

