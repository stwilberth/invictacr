<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waitlist_entries', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono')->nullable();
            $table->string('modelo')->index();
            $table->string('nota')->nullable();
            $table->string('estado')->default('pendiente')->index();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('waitlist_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waitlist_entry_id')->nullable()->constrained('waitlist_entries')->nullOnDelete();
            $table->string('modelo')->index();
            $table->string('titulo');
            $table->text('mensaje')->nullable();
            $table->timestamp('leida_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waitlist_notifications');
        Schema::dropIfExists('waitlist_entries');
    }
};
