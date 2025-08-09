<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المنتج
            $table->unsignedBigInteger('brand_id'); // مرتبط بالبراند
            $table->unsignedBigInteger('category_id'); // مرتبط بالقسم
            $table->unsignedBigInteger('car_id'); // مرتبط بالقسم
            $table->decimal('price', 10, 2); // السعر
            $table->decimal('profit_margin', 5, 2); // هامش الربح
            $table->unsignedInteger('quantity_shop')->default(0);   // كمية المحل
            $table->unsignedInteger('quantity_store')->default(0);  // كمية المخزن
            $table->string('image')->nullable(); // الصورة
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
