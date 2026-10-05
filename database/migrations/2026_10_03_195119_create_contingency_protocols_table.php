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
        Schema::create('contingency_protocols', function (Blueprint $table) {
        $table->id();
        $table->enum('category', ['co2', 'temperature', 'humidity'])->comment('Categoría de variable ambiental');
        $table->enum('risk_level', ['warning', 'danger'])->default('warning')->comment('Nivel 1 Advertencia, Nivel 2 Alerta Crítica');
        $table->string('title')->comment('Título breve de la recomendación');
        $table->text('description')->comment('Descripción y contexto del riesgo');
        $table->text('action_steps')->comment('Pasos de actuación e instrucciones de ventilación/evacuación');
        $table->boolean('is_active')->default(true);
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contingency_protocols');
    }
};
