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
        $table->id();
        $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
        $table->string('nombre_ruta');
        $table->string('geojson_file');  // Ruta al archivo geojson
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};
