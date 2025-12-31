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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->float('longitude')->nullable();
            $table->float('latitude')->nullable();
            $table->foreignId('city_id')->nullable()->references('id')->on("yy_cities")->onDelete('restrict');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->bigInteger('postcode')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
