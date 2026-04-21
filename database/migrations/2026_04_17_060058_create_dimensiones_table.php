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
<<<<<<< HEAD
            $table->decimal('ancho', 12, 2);
            $table->decimal('largo', 12, 2);
            $table->decimal('area', 12, 2)->storedAs('ancho * largo');
=======
            $table->decimal('ancho', 8, 2);
            $table->decimal('largo', 8, 2);
            $table->decimal('area', 8, 2)->storedAs('ancho * largo');
>>>>>>> 665fe70f9df4c506ced3c6beb45900d4c0698f0c
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
