<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained()->cascadeOnDelete();
            $table->string('session_id', 64)->index();
            $table->string('type', 30)->index();
            $table->text('url')->nullable();
            $table->string('page_title')->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->nullable();

            $table->index(['visitor_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_events');
    }
};
