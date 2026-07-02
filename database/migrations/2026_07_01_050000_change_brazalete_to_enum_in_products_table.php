<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $brazaletes = config('brazaletes', []);

        if (empty($brazaletes)) {
            return;
        }

        // Normalize existing data: lowercase and trim
        DB::statement("UPDATE products SET brazalete = NULL WHERE brazalete IS NOT NULL AND TRIM(brazalete) = ''");
        DB::statement("UPDATE products SET brazalete = LOWER(TRIM(brazalete)) WHERE brazalete IS NOT NULL");

        // Set values not in our list to NULL
        $placeholders = implode(',', array_fill(0, count($brazaletes), '?'));
        $lowerValues = array_map('strtolower', $brazaletes);
        DB::statement(
            "UPDATE products SET brazalete = NULL WHERE brazalete IS NOT NULL AND brazalete NOT IN ($placeholders)",
            $lowerValues
        );

        // Map lowercase back to proper case
        foreach ($brazaletes as $value) {
            DB::statement(
                'UPDATE products SET brazalete = ? WHERE brazalete = ?',
                [$value, mb_strtolower($value)]
            );
        }

        // Change column to ENUM
        $enumValues = implode("','", $brazaletes);
        DB::statement("ALTER TABLE products MODIFY brazalete ENUM('$enumValues') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE products MODIFY brazalete VARCHAR(255) NULL");
    }
};
