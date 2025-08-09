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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('location', ['shop', 'store'])->default('shop'); // اتسحب منين؟
            $table->unsignedInteger('shop_qty_old')->nullable();
            $table->unsignedInteger('shop_qty_new')->nullable();
            $table->unsignedInteger('store_qty_old')->nullable();
            $table->unsignedInteger('store_qty_new')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('selling_price', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
