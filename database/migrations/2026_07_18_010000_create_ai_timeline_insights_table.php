<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_timeline_insights', function (Blueprint $table) {
            $table->id();
            $table->string('period_key', 20)->unique();
            $table->string('period_label', 50);
            $table->text('conclusion')->nullable();
            $table->text('advice')->nullable();
            $table->boolean('is_final')->default(false);
            $table->json('raw_data')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_timeline_insights');
    }
};
