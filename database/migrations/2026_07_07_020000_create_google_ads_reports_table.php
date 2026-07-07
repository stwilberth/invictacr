<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('google_ads_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->string('campaign_name')->nullable();
            $table->string('campaign_id')->nullable();
            $table->decimal('impressions', 12, 0)->default(0);
            $table->decimal('clicks', 12, 0)->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('conversions', 10, 2)->default(0);
            $table->decimal('conversion_value', 12, 2)->default(0);
            $table->decimal('ctr', 8, 4)->default(0);
            $table->decimal('average_cpc', 10, 4)->default(0);
            $table->json('raw_data')->nullable();
            $table->timestamps();

            $table->index('report_date');
            $table->index('campaign_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('google_ads_reports');
    }
};
