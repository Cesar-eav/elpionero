<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cable_a_tierra', function (Blueprint $table) {
            $table->string('video_youtube')->nullable()->after('imagen_desktop');
        });
    }

    public function down(): void
    {
        Schema::table('cable_a_tierra', function (Blueprint $table) {
            $table->dropColumn('video_youtube');
        });
    }
};
