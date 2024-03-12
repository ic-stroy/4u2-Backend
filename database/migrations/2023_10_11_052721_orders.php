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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->double('price')->nullable();
            $table->integer('status')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->double('delivery_price')->nullable();
            $table->double('all_price')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->integer('address_id')->nullable();
            $table->string('receiver_name', 100)->nullable();
            $table->string('phone_number')->nullable();
            $table->integer('payment_method')->nullable();
            $table->integer('user_card_id')->nullable();
            $table->double('discount_price')->nullable();
            $table->double('coupon_price')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
