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
        Schema::table('entrers', function (Blueprint $table) {
            $table->float('kilo_once_quantity')->default(0);
            $table->dropColumn('kilo_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entrers', function (Blueprint $table) {
            //
        });
    }
};
