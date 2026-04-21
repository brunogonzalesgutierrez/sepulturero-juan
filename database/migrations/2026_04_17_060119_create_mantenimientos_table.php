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
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('espacio_id')->constrained('espacios')->restrictOnDelete();
            $table->text('descripcion');
            $table->decimal('precio', 10, 2);
            $table->enum('tipo', ['limpieza', 'reparacion', 'renovacion', 'otro']);
            $table->enum('estado', ['pendiente', 'en_proceso', 'completado'])->default('pendiente');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};
