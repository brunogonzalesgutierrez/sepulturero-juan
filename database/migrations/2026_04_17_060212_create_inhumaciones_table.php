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
        Schema::create('inhumaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('espacio_id')->constrained('espacios')->restrictOnDelete();
            $table->foreignId('contrato_id')->constrained('contratos')->restrictOnDelete();
            $table->string('nombre');
            $table->string('paterno');
            $table->string('materno')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->date('fecha_defuncion');
            $table->date('fecha_inhumacion');
            $table->string('causa_muerte')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inhumaciones');
    }
};
