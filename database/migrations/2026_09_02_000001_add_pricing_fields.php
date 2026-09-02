<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("products", function (Blueprint $table) {
            $table->decimal("precio_costo", 10, 2)->nullable()->after("precio_original");
            $table->boolean("manual_override")->default(false)->after("proximo");
        });

        Schema::table("invoice_items", function (Blueprint $table) {
            $table->decimal("unit_cost", 12, 2)->nullable()->after("unit_price");
        });
    }

    public function down(): void
    {
        Schema::table("products", function (Blueprint $table) {
            $table->dropColumn(["precio_costo", "manual_override"]);
        });

        Schema::table("invoice_items", function (Blueprint $table) {
            $table->dropColumn("unit_cost");
        });
    }
};
