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
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Relasi ke tabel produk
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade'); // Relasi ke varian produk
            $table->integer('quantity')->unsigned();
            $table->decimal('total_price', 18, 2);
            $table->timestamps(); // Ini akan membuat created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
