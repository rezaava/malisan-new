<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konkorqs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('question');
            $table->text('answer1');
            $table->text('answer2');
            $table->text('answer3');
            $table->text('answer4');
            $table->integer('answer');
            $table->integer('checker')->nullable();
            $table->integer('status')->nullable();
            $table->string('year', 191)->nullable();
            $table->integer('konkor_id');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konkorqs');
    }
};