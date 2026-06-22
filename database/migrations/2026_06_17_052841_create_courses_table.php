<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->integer('archieve')->default(0);
            $table->string('header', 191)->nullable();
            $table->string('code', 191);
            $table->integer('private')->default(0);
            $table->integer('period')->default(3);
            $table->integer('type')->default(0);
            $table->text('desc')->nullable();
            $table->integer('price')->default(0);
            $table->integer('length')->default(0);
            $table->integer('sessions_length')->default(0);
            $table->text('majazi')->nullable();
            $table->integer('max_session')->default(16);
            $table->integer('num_q')->default(3);
            $table->integer('score_e')->default(0);
            $table->integer('score_d')->default(0);
            $table->integer('score_q')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->integer('active')->default(1);
            $table->integer('quiz')->default(1);
            $table->integer('davari')->default(1);
            $table->integer('faaliat')->default(1);
            $table->integer('pishraft')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};