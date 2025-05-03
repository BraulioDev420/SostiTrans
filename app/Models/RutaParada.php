<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRutaParadaTable extends Migration
{
    public function up()
    {
        Schema::create('ruta_parada', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruta_id')->constrained()->onDelete('cascade'); // Relaciona con la tabla rutas
            $table->foreignId('parada_id')->constrained()->onDelete('cascade'); // Relaciona con la tabla paradas
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ruta_parada');
    }
}
