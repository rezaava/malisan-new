<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercise_answers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('exercise_id');
            $table->text('answer')->nullable();
            $table->string('file', 191)->nullable();
            $table->integer('status')->nullable()->comment('1.ali 2.khob 3.motevaset 4.bad');
            $table->string('comment', 191)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercise_answers');
    }
};