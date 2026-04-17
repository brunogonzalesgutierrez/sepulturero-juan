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
            $table->integer('espacio_disponible')->default(0);
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
