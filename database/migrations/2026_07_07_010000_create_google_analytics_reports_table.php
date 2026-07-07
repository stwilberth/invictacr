<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('google_analytics_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->integer('users')->default(0);
            $table->integer('sessions')->default(0);
            $table->integer('pageviews')->default(0);
            $table->decimal('bounce_rate', 5, 2)->default(0);
            $table->decimal('avg_session_duration', 10, 2)->default(0);
            $table->integer('new_users')->default(0);
            $table->text('top_pages')->nullable();
            $table->text('traffic_sources')->nullable();
            $table->text('device_breakdown')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();

            $table->unique('report_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('google_analytics_reports');
    }
};
