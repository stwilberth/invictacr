<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('story_histories', function (Blueprint $table) {
            $table->string('channel')->default('facebook')->after('story_id');
        });
    }

    public function down(): void
    {
        Schema::table('story_histories', function (Blueprint $table) {
            $table->dropColumn(['channel']);
        });
    }
};
