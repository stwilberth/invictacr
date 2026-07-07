<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_factors', function (Blueprint $table) {
            $table->id();
            $table->date('event_date');
            $table->string('category'); // war, inflation, season, world_cup, holiday, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('source')->nullable();
            $table->string('impact_level')->default('medium'); // low, medium, high, critical
            $table->boolean('active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('event_date');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_factors');
    }
};
