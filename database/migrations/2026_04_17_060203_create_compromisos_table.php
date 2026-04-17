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
        Schema::create('compromisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_pago_id')->constrained('plan_pagos')->restrictOnDelete();
            $table->foreignId('cuota_id')->constrained('cuotas')->restrictOnDelete();
            $table->date('fecha_compromiso');
            $table->integer('plazo_dias');
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compromisos');
    }
};
