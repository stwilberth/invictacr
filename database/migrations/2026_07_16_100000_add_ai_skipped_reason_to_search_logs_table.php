<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('search_logs', function (Blueprint $table) {
            $table->string('ai_skipped_reason', 50)->nullable()->after('ai_raw_response');
            $table->json('suggestions')->nullable()->after('results_count');
        });
    }

    public function down(): void
    {
        Schema::table('search_logs', function (Blueprint $table) {
            $table->dropColumn(['ai_skipped_reason', 'suggestions']);
        });
    }
};
