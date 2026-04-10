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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->decimal('total_price', 15, 2); // Total belanjaan
            $table->decimal('cash_received', 15, 2); // Uang yang dibayar pembeli
            $table->decimal('cash_return', 15, 2); // Kembalian
            $table->foreignId('user_id')->constrained(); // Mencatat siapa kasirnya (User ID)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
