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
        Schema::create('stock_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('sale_item_id')->nullable();
            $table->unsignedBigInteger('movement_id')->nullable(); //
            $table->integer('old_shop_qty')->nullable();
            $table->integer('old_store_qty')->nullable();
            $table->integer('new_shop_qty')->nullable();
            $table->integer('new_store_qty')->nullable();
            $table->string('change_type'); // "إنشاء منتج"، "إضافة مخزون"، "خصم بيع"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_audits');
    }
};
