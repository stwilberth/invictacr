<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('story_histories', function (Blueprint $table) {
            $table->string('post_id')->nullable()->after('story_id');
            $table->integer('reactions')->default(0)->after('replies');
            $table->integer('shares')->default(0)->after('reactions');
        });
    }

    public function down(): void
    {
        Schema::table('story_histories', function (Blueprint $table) {
            $table->dropColumn(['post_id', 'reactions', 'shares']);
        });
    }
};
