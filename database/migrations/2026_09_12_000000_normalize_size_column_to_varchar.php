<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * La columna size era DECIMAL(5,1) y MySQL devolvía todo con ".0"
     * (40 → "40.0"). Se vuelve a VARCHAR y se normalizan los valores:
     * 40.0 → 40 (los decimales reales como 47.5 se conservan).
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN size VARCHAR(50) NULL");
        DB::statement("UPDATE products SET size = TRIM(TRAILING '.' FROM TRIM(TRAILING '0' FROM size)) WHERE size LIKE '%.0'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN size DECIMAL(5,1) NULL");
    }
};
