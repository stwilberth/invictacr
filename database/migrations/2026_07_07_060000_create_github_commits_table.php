<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('github_commits', function (Blueprint $table) {
            $table->id();
            $table->string('sha')->unique();
            $table->string('message');
            $table->string('author_name');
            $table->string('author_email')->nullable();
            $table->string('branch')->default('main');
            $table->string('repository');
            $table->timestamp('committed_at');
            $table->integer('additions')->default(0);
            $table->integer('deletions')->default(0);
            $table->integer('files_changed')->default(0);
            $table->text('files_summary')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();

            $table->index('committed_at');
            $table->index('branch');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('github_commits');
    }
};
