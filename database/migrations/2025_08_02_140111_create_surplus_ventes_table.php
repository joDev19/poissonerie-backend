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
        Schema::create('surplus_ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vente_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('selled_product_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('excess_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surplus_ventes');
    }
};
