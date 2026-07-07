<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_console_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->string('query')->nullable();
            $table->string('page')->nullable();
            $table->string('country')->nullable();
            $table->string('device')->nullable();
            $table->integer('clicks')->default(0);
            $table->integer('impressions')->default(0);
            $table->decimal('ctr', 8, 4)->default(0);
            $table->decimal('position', 6, 2)->default(0);
            $table->json('raw_data')->nullable();
            $table->timestamps();

            $table->index('report_date');
            $table->index('query');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_console_reports');
    }
};
