<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique()->nullable(); // Untuk scan/kode barang
            $table->string('name');
            $table->string('category')->nullable(); // Gas, Sembako, Ikan, dll
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5); // Notifikasi jika stok sisa sedikit
            $table->decimal('purchase_price', 15, 2); // Harga modal (untuk hitung untung)
            $table->decimal('sell_price', 15, 2); // Harga jual
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
