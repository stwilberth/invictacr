<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facebook_insights', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->string('page_id')->nullable();
            $table->string('page_name')->nullable();
            $table->integer('page_impressions')->default(0);
            $table->integer('page_engaged_users')->default(0);
            $table->integer('page_follows')->default(0);
            $table->integer('page_reactions')->default(0);
            $table->integer('page_comments')->default(0);
            $table->integer('page_shares')->default(0);
            $table->decimal('page_views', 10, 0)->default(0);
            $table->json('raw_data')->nullable();
            $table->timestamps();

            $table->unique(['report_date', 'page_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facebook_insights');
    }
};
