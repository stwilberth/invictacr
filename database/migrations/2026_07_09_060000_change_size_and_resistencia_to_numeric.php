<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nullify invalid size values
        DB::statement("UPDATE products SET size = NULL WHERE size IS NOT NULL AND size NOT REGEXP '^-?[0-9]+(\\.[0-9]+)?$'");

        // Change size to decimal(5,1)
        DB::statement("ALTER TABLE products MODIFY COLUMN size DECIMAL(5,1) NULL");

        // Nullify non-numeric resistencia_agua and change to integer
        DB::statement("UPDATE products SET resistencia_agua = NULL WHERE resistencia_agua IS NOT NULL AND resistencia_agua NOT REGEXP '^-?[0-9]+$'");
        DB::statement("ALTER TABLE products MODIFY COLUMN resistencia_agua INT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN size VARCHAR(255) NULL");
        DB::statement("ALTER TABLE products MODIFY COLUMN resistencia_agua VARCHAR(255) NULL");
    }
};
