<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_log_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sync_log_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('modelo');
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_log_items');
    }
};
