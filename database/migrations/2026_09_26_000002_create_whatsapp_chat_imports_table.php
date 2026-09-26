<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_chat_imports', function (Blueprint $table) {
            $table->id();
            $table->string('source_name');
            $table->char('content_hash', 64)->unique();
            $table->string('contact_name')->nullable();
            $table->string('phone', 30)->nullable();
            $table->longText('transcript');
            $table->json('message_timestamps')->nullable();
            $table->unsignedInteger('message_count')->default(0);
            $table->timestamp('first_message_at')->nullable()->index();
            $table->timestamp('last_message_at')->nullable()->index();
            $table->foreignId('visitor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('imported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['visitor_id', 'first_message_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_chat_imports');
    }
};
