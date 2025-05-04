<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->id(); // ID de la ruta
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con el usuario
            $table->string('nombre_ruta'); // Nombre de la ruta
            $table->string('origen'); // Origen de la ruta (coordenadas o dirección)
            $table->string('destino'); // Destino de la ruta (coordenadas o dirección)
            $table->string('bus_sugerido'); // Bus sugerido por el sistema
            $table->string('geojson_file', 255);
            $table->timestamps(); // Fechas de creación y actualización
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutas'); // Eliminar la tabla 'rutas' si existe
    }
};

