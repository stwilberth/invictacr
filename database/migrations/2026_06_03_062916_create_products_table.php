<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('modelo')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();
            $table->string('color')->nullable();
            $table->string('brazalete')->nullable();
            $table->string('coleccion')->nullable();
            $table->string('tipo_movimiento')->nullable();
            $table->string('size')->nullable();
            $table->string('genero')->nullable();
            $table->string('caja')->nullable();
            $table->string('resistencia_agua')->nullable();
            $table->string('video')->nullable();
            $table->decimal('precio_venta', 10, 2)->default(0);
            $table->decimal('precio_original', 10, 2)->nullable();
            $table->integer('descuento')->default(0);
            $table->integer('stock')->default(0);
            $table->string('imagen')->nullable();
            $table->boolean('isGif')->default(false);
            $table->boolean('activo')->default(true);
            $table->json('imagenes_extra')->nullable();
            $table->json('caracteristicas')->nullable();
            $table->integer('vistas')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
