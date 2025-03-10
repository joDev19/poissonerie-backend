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
        Schema::create('entrers', function (Blueprint $table) {
            $table->id();
            $table->float("price");
            $table->date("date");
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->float("kilo_quantity");
            $table->float("box_quantity");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrers');
    }
};
