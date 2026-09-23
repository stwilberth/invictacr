<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tracking de publicaciones orgánicas: id del post en Meta para
     * consultar insights después, y canal que lo publicó.
     */
    public function up(): void
    {
        Schema::table('download_histories', function (Blueprint $table) {
            $table->string('post_id')->nullable()->after('text_content');
            $table->string('channel', 20)->default('facebook')->after('post_id');
        });
    }

    public function down(): void
    {
        Schema::table('download_histories', function (Blueprint $table) {
            $table->dropColumn(['post_id', 'channel']);
        });
    }
};
