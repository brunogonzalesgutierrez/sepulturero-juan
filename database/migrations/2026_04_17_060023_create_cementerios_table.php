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
        Schema::create('cementerios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('localizacion');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
<<<<<<< HEAD
            $table->integer('espacio_total')->default(0);
=======
            $table->integer('espacio_disponible')->default(0);
>>>>>>> 665fe70f9df4c506ced3c6beb45900d4c0698f0c
            $table->string('tipo_cementerio');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cementerios');
    }
};
