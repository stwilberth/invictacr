<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_ceo_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('batch_key', 20); // ej: 2026-08-25_1830, agrupa las recomendaciones generadas juntas
            $table->string('category', 20); // urgente | oportunidad | estrategia
            $table->string('priority', 10); // alta | media | baja
            $table->string('title', 180);
            $table->text('rationale'); // qué pasó / por qué importa (con datos)
            $table->text('action'); // acción concreta sugerida
            $table->string('status', 20)->default('pendiente'); // pendiente | hecho | descartado
            $table->json('raw_data')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['batch_key']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_ceo_recommendations');
    }
};
