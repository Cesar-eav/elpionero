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
        Schema::table('denuncias', function (Blueprint $table) {
            $table->string('imagen4')->nullable()->after('imagen3');
        });
    }

    public function down(): void
    {
        Schema::table('denuncias', function (Blueprint $table) {
            $table->dropColumn('imagen4');
        });
    }
};
