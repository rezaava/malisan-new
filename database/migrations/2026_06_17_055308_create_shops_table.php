<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->mediumText('description')->nullable();
            $table->integer('discount_percent');
            $table->mediumText('address')->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('latitude', 255);
            $table->string('longitude', 255);
            $table->string('image', 255)->nullable();
            $table->integer('user_id');
            $table->integer('active')->default(0);
            $table->string('checkout_sheba', 255)->nullable();
            $table->string('checkout_name', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};