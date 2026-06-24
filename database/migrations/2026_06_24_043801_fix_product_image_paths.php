<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Corrige las rutas de imágenes de /assets/relojes/ a /storage/relojes/
     * para que se sirvan vía el symlink estándar public/storage de Laravel.
     */
    public function up(): void
    {
        DB::table("products")
            ->where("imagen", "like", "/assets/relojes/%")
            ->update([
                "imagen" => DB::raw(
                    "REPLACE(imagen, '/assets/relojes/', '/storage/relojes/')",
                ),
            ]);
    }

    public function down(): void
    {
        DB::table("products")
            ->where("imagen", "like", "/storage/relojes/%")
            ->update([
                "imagen" => DB::raw(
                    "REPLACE(imagen, '/storage/relojes/', '/assets/relojes/')",
                ),
            ]);
    }
};
