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
        Schema::create('characterized_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('size_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('color_id')->nullable()->references('id')->on('color')->onDelete('restrict');
            $table->integer('count')->nullable();
            $table->integer('sum')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characterized_products');
    }
};
