<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'disponibilidad')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('disponibilidad')->default('disponible')->after('stock');
            });

            DB::statement("UPDATE products SET disponibilidad = CASE WHEN stock <= 0 THEN 'agotado' ELSE 'disponible' END");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'disponibilidad')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('disponibilidad');
            });
        }
    }
};
