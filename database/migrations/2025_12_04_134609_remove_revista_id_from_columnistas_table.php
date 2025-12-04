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
        Schema::table('columnistas', function (Blueprint $table) {
            $table->dropForeign(['revista_id']);
            $table->dropColumn('revista_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('columnistas', function (Blueprint $table) {
            $table->foreignId('revista_id')->nullable()->constrained('revistas')->onDelete('set null');
        });
    }
};
