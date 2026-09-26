<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
            $table->string('phone_norm', 16)->nullable()->change();
        });
    }

    public function down(): void
    {
        // Keep nullable values intact rather than making rollback fail on existing records.
    }
};
