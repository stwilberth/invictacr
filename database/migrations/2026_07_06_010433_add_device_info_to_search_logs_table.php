<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('search_logs', function (Blueprint $table) {
            $table->string('real_ip', 45)->nullable()->after('ip_address');
            $table->text('user_agent')->nullable()->after('real_ip');
            $table->string('device_type', 20)->nullable()->after('user_agent');
        });
    }

    public function down(): void
    {
        Schema::table('search_logs', function (Blueprint $table) {
            $table->dropColumn(['real_ip', 'user_agent', 'device_type']);
        });
    }
};
