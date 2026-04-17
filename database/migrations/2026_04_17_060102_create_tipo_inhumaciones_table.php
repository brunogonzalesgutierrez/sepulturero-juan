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
        Schema::create('tipo_inhumaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('precio', 10, 2);
            $table->decimal('precio_base', 10, 2);
            $table->integer('capacidad_max');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->decimal('area_base', 8, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_inhumaciones');
    }
};
