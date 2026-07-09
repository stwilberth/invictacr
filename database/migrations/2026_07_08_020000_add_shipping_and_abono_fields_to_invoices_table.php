<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->text('customer_address')->nullable()->after('client_phone');
            $table->decimal('shipping', 12, 2)->default(0)->after('discount');
            $table->decimal('shipping_cost', 12, 2)->nullable()->after('shipping');
            $table->string('shipping_status')->default('pendiente')->after('status');
            $table->date('delivery_date')->nullable()->after('shipping_status');
            $table->string('delivery_time_start')->nullable()->after('delivery_date');
            $table->string('delivery_time_end')->nullable()->after('delivery_time_start');
            $table->text('location')->nullable()->after('delivery_time_end');
            $table->boolean('needs_bracelet_adjustment')->default(false)->after('location');
            $table->date('creation_date')->nullable()->after('needs_bracelet_adjustment');
            $table->decimal('estimated_utility', 12, 2)->nullable()->after('creation_date');
            $table->string('cedula')->nullable()->after('estimated_utility');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'customer_address',
                'shipping',
                'shipping_cost',
                'shipping_status',
                'delivery_date',
                'delivery_time_start',
                'delivery_time_end',
                'location',
                'needs_bracelet_adjustment',
                'creation_date',
                'estimated_utility',
                'cedula',
            ]);
        });
    }
};
