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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('warehouse_id')->nullable()->references('id')->on('characterized_products')->onDelete('restrict');
            $table->integer('quantity');
            $table->integer('discount')->nullable();
            $table->double('discount_price')->nullable();
            $table->double('price')->nullable();
            $table->foreignId('size_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('color_id')->nullable()->references('id')->on('color')->onDelete('restrict');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
