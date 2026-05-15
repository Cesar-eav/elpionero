<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('denuncias', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('titulo');
        });

        // Generar slugs para registros existentes
        DB::table('denuncias')->orderBy('id')->each(function ($d) {
            $base = Str::slug($d->titulo);
            $slug = $base;
            $i = 1;
            while (DB::table('denuncias')->where('slug', $slug)->where('id', '!=', $d->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('denuncias')->where('id', $d->id)->update(['slug' => $slug]);
        });
    }

    public function down(): void
    {
        Schema::table('denuncias', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
