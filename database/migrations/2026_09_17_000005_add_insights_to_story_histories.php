<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('story_histories', function (Blueprint $table) {
            $table->integer('views')->default(0)->after('channel');
            $table->integer('impressions')->default(0)->after('views');
            $table->integer('reach')->default(0)->after('impressions');
            $table->integer('replies')->default(0)->after('reach');
        });
    }

    public function down(): void
    {
        Schema::table('story_histories', function (Blueprint $table) {
            $table->dropColumn(['views', 'impressions', 'reach', 'replies']);
        });
    }
};