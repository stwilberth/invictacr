<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facebook_ad_reports', function (Blueprint $table) {
            $table->string('campaign_objective', 50)->nullable()->after('campaign_id');
            $table->string('campaign_status', 20)->nullable()->after('campaign_name');
            $table->string('adset_name')->nullable()->after('campaign_status');
            $table->string('adset_id')->nullable()->after('adset_name');
            $table->string('ad_name')->nullable()->after('adset_id');
            $table->string('ad_id')->nullable()->after('ad_name');
            $table->decimal('conversions', 12, 2)->default(0)->after('ctr');
            $table->decimal('conversion_value', 14, 2)->default(0)->after('conversions');
            $table->decimal('roas', 8, 2)->default(0)->after('conversion_value');
            $table->decimal('cost_per_conversion', 10, 2)->default(0)->after('roas');
            $table->decimal('cpp', 10, 4)->default(0)->after('cost_per_conversion');
            $table->decimal('unique_clicks', 12, 0)->default(0)->after('cpp');
            $table->decimal('inline_link_clicks', 12, 0)->default(0)->after('unique_clicks');
            $table->decimal('inline_post_engagement', 12, 0)->default(0)->after('inline_link_clicks');
            $table->string('level', 20)->default('campaign')->after('inline_post_engagement');
            $table->date('date_start')->nullable()->after('level');
            $table->date('date_stop')->nullable()->after('date_start');
        });
    }

    public function down(): void
    {
        Schema::table('facebook_ad_reports', function (Blueprint $table) {
            $table->dropColumn([
                'campaign_objective', 'campaign_status', 'adset_name', 'adset_id',
                'ad_name', 'ad_id', 'conversions', 'conversion_value', 'roas',
                'cost_per_conversion', 'cpp', 'unique_clicks', 'inline_link_clicks',
                'inline_post_engagement', 'level', 'date_start', 'date_stop',
            ]);
        });
    }
};