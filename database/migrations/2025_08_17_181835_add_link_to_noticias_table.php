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
            if (!Schema::hasColumn('noticias', 'link')) {
                $table->string('link')->nullable()->after('imagen');
            }
            if (!Schema::hasColumn('noticias', 'slug')) {
                $table->string('slug')->unique()->after('titulo');
            }
            if (!Schema::hasColumn('noticias', 'fecha_publicacion')) {
                $table->timestamp('fecha_publicacion')->nullable()->after('cuerpo');
            }
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
