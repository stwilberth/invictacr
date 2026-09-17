<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('narrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->text('script');
            $table->string('audio_path'); // ruta pública /storage/narraciones/xxx.mp3
            $table->string('voice_id')->default('9XaoraKgpXhItOQktYsV');
            $table->string('provider')->default('elevenlabs');
            $table->timestamps();

            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('narrations');
    }
};
