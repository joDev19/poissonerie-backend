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
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price_kilo');
            $table->dropColumn('price_unit');
            $table->dropColumn('price_carton');
            $table->float('price_unit_min')->default(0);
            $table->float('price_unit_max')->default(0);
            $table->float('price_kilo_min')->default(0);
            $table->float('price_kilo_max')->default(0);
            $table->float('price_carton_min')->default(0);
            $table->float('price_carton_max')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
