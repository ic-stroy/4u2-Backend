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
            $table->integer('receiver_name')->nullable();
            $table->integer('phone_number')->nullable();
            $table->integer('payment_method')->nullable();
            $table->integer('user_card_id')->nullable();
            $table->double('coupon_price', 15, 8)->nullable();
            $table->integer('code')->nullable();
            $table->foreign('user_card_id')->references('id')->on('user_cards');
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->foreign('user_id')->references('id')->on('users');
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
