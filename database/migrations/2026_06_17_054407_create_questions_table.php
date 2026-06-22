<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->text('answer1');
            $table->text('answer2');
            $table->text('answer3');
            $table->text('answer4');
            $table->text('answer');
            $table->bigInteger('user_id');
            $table->bigInteger('session_id');
            $table->integer('status')->nullable()->comment('1.ali 2.khob 3.motevaset 4.bad');
            $table->integer('star')->default(0);
            $table->integer('counter')->default(0);
            $table->integer('is_edit')->default(0);
            $table->integer('score')->default(0);
            $table->enum('level', ['1', '2']);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};