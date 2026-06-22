<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tourusers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('tour_id');
            $table->mediumText('title');
            $table->mediumText('abstract');
            $table->mediumText('keywords');
            $table->mediumText('file');
            $table->integer('davar')->nullable();
            $table->text('desc_title')->nullable();
            $table->text('desc_key')->nullable();
            $table->text('desc_abs')->nullable();
            $table->text('desc_file')->nullable();
            $table->integer('score_file')->nullable();
            $table->integer('score_abs')->nullable();
            $table->integer('score_key')->nullable();
            $table->integer('score_title')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tourusers');
    }
};