<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facebook_posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_id')->unique();
            $table->text('message')->nullable();
            $table->string('link')->nullable();
            $table->string('media_type')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->integer('likes')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('shares')->default(0);
            $table->integer('reach')->default(0);
            $table->integer('impressions')->default(0);
            $table->json('raw_data')->nullable();
            $table->timestamps();

            $table->index('posted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facebook_posts');
    }
};
