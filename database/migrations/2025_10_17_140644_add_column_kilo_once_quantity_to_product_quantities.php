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
        Schema::table('product_quantities', function (Blueprint $table) {
            // ajouter la coloumn kilo_once_quantity pour garder la quantité de chaque carton lors de cette aprovisionnement ( il peut être null )
            $table->float('kilo_once_quantity')->nullable();
            $table->float('price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_quantities', function (Blueprint $table) {
            //
        });
    }
};
