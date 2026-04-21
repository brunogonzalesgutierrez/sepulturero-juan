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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuota_id')->constrained('cuotas')->restrictOnDelete();
            $table->foreignId('empleado_id')->constrained('empleados')->restrictOnDelete();
            $table->date('fecha_pago');
            $table->decimal('monto_pagado', 10, 2);
            $table->decimal('monto_interes', 10, 2)->default(0);
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta', 'qr']);
            $table->string('comprobante')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
