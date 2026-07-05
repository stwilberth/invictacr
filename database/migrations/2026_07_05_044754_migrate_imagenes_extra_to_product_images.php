<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'imagenes_extra')) {
            return;
        }

        DB::table('products')
            ->whereNotNull('imagenes_extra')
            ->orderBy('id')
            ->each(function ($product) {
                $urls = json_decode($product->imagenes_extra, true) ?? [];
                $rows = [];
                foreach ($urls as $order => $url) {
                    $rows[] = [
                        'product_id' => $product->id,
                        'url' => $url,
                        'order' => $order,
                        'type' => 'image',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if (!empty($rows)) {
                    DB::table('product_images')->insert($rows);
                }
            });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('imagenes_extra');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('imagenes_extra')->nullable()->after('imagen');
        });

        DB::table('product_images')
            ->orderBy('product_id')
            ->orderBy('order')
            ->get()
            ->groupBy('product_id')
            ->each(function ($images, $productId) {
                $urls = $images->pluck('url')->values()->toArray();
                DB::table('products')
                    ->where('id', $productId)
                    ->update(['imagenes_extra' => json_encode($urls)]);
            });
    }
};
