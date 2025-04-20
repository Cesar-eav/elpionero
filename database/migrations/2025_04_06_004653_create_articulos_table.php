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
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revista_id')->constrained()->onDelete('cascade'); // Clave foránea a la tabla revistas
            $table->string('titulo');
            $table->string('slug')->unique(); // Para URLs amigables
            $table->text('contenido');
            $table->string('autor')->nullable();
            $table->string('imagen_autor'); 
            $table->string('seccion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
