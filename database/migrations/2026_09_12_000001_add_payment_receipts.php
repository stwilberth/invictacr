<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Comprobantes de pago (capturas) en R2:
     * - abonos.comprobante_path: un comprobante por abono (apartados).
     * - invoice_receipts: múltiples comprobantes por factura (contado).
     */
    public function up(): void
    {
        Schema::table('abonos', function (Blueprint $table) {
            $table->string('comprobante_path', 500)->nullable()->after('note');
        });

        Schema::create('invoice_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('path', 500);
            $table->string('original_name', 255)->nullable();
            $table->string('mime', 100)->nullable();
            $table->unsignedInteger('size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_receipts');
        Schema::table('abonos', function (Blueprint $table) {
            $table->dropColumn('comprobante_path');
        });
    }
};
