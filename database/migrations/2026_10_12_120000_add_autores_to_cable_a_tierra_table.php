<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cable_a_tierra', function (Blueprint $table) {
            // [{nombre: string, foto: ?string}] — `autor` se mantiene como texto con los nombres unidos
            $table->json('autores')->nullable()->after('autor');
        });

        // Migrar el autor único existente al nuevo formato
        DB::table('cable_a_tierra')
            ->whereNotNull('autor')
            ->where('autor', '!=', '')
            ->orderBy('id')
            ->each(function ($row) {
                DB::table('cable_a_tierra')->where('id', $row->id)->update([
                    'autores' => json_encode([['nombre' => $row->autor, 'foto' => null]]),
                ]);
            });
    }

    public function down(): void
    {
        Schema::table('cable_a_tierra', function (Blueprint $table) {
            $table->dropColumn('autores');
        });
    }
};
