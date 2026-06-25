<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("products", function (Blueprint $table) {
            $table->boolean("bloqueado")->default(false)->after("activo");
            $table
                ->integer("variedades_price")
                ->nullable()
                ->after("precio_original");
            $table
                ->integer("variedades_increase")
                ->nullable()
                ->after("variedades_price");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("products", function (Blueprint $table) {
            $table->dropColumn([
                "bloqueado",
                "variedades_price",
                "variedades_increase",
            ]);
        });
    }
};
