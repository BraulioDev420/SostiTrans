<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rutas', function (Blueprint $table) {
            $table->longText(column: 'instrucciones_caminando')->nullable()->after('geojson_file');
            $table->longText('geojson_caminando')->nullable()->after('instrucciones_caminando');
        });
    }

    public function down(): void
    {
        Schema::table('rutas', function (Blueprint $table) {
            $table->dropColumn(['instrucciones_caminando', 'geojson_caminando']);
        });
    }
};
