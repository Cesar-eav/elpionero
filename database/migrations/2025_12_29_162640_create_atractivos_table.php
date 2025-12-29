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
        Schema::create('atractivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->longText('description');
            $table->string('category');
            $table->json('tags')->nullable();
            $table->string('image')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('enlace')->nullable();
            $table->string('autor')->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->string('horario')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atractivos');
    }
};
