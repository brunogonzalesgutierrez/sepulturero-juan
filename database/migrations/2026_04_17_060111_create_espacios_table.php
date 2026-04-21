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
        Schema::create('espacios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cementerio_id')->constrained('cementerios')->restrictOnDelete();
            $table->foreignId('dimension_id')->constrained('dimensiones')->restrictOnDelete();
            $table->foreignId('tipo_inhumacion_id')->constrained('tipo_inhumaciones')->restrictOnDelete();
            $table->enum('estado', ['disponible', 'ocupado', 'mantenimiento', 'reservado'])->default('disponible');
            $table->decimal('precio_m2', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('espacios');
    }
};
