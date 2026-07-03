<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("products", function (Blueprint $table) {
            $table->dropColumn(["isGif", "variedades_price"]);
        });
    }

    public function down(): void
    {
        Schema::table("products", function (Blueprint $table) {
            $table->boolean("isGif")->default(false)->after("imagen");
            $table->integer("variedades_price")->nullable()->after("precio_original");
        });
    }
};
