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
        Schema::table('noticias', function (Blueprint $table) {
            $table->string('link')->nullable()->after('imagen'); // Agrega la columna 'link' después de 'imagen'
            $table->string('slug')->unique()->after('titulo'); // Agrega la columna 'slug' después de 'titulo'
            $table->timestamp('fecha_publicacion')->nullable()->after('cuerpo'); // Agrega la columna 'fecha_publicacion' después de 'cuerpo'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noticias', function (Blueprint $table) {
            $table->dropColumn('link'); // Elimina la columna 'link'
            $table->dropColumn('slug'); // Elimina la columna 'slug'
            $table->dropColumn('fecha_publicacion'); // Elimina la columna 'fecha_publicacion'
        });
    }
};
