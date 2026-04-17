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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('espacio_id')->constrained('espacios')->restrictOnDelete();
            $table->date('fecha_contrato');
            $table->decimal('monto_base', 10, 2);
            $table->decimal('saldo_pendiente', 10, 2)->default(0);
            $table->enum('estado', ['activo', 'pagado', 'vencido', 'cancelado'])->default('activo');
            $table->enum('moneda', ['BOB', 'USD'])->default('BOB');
            $table->text('observacion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
