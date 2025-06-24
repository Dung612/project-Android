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
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('open_time');
            $table->dropColumn('close_time');
            $table->dropColumn('price');
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->text('description')->nullable();
        });
    }
}; 