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
        Schema::create('dimensiones', function (Blueprint $table) {
            $table->id();
            $table->decimal('ancho', 12, 2);
            $table->decimal('largo', 12, 2);
            $table->decimal('area', 12, 2)->storedAs('ancho * largo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dimensiones');
    }
};
