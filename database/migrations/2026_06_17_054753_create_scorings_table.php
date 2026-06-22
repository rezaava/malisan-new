<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scorings', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->string('q_1', 191)->default('1');
            $table->string('q_2', 191)->default('0.5');
            $table->string('q_3', 191)->default('0.25');
            $table->string('q_4', 191)->default('-0.25');
            $table->string('d_1', 191)->default('1');
            $table->string('d_2', 191)->default('0.8');
            $table->string('d_3', 191)->default('0.65');
            $table->string('d_4', 191)->default('-0.25');
            $table->string('e_1', 191)->default('1');
            $table->string('e_2', 191)->default('0.8');
            $table->string('e_3', 191)->default('0.65');
            $table->string('e_4', 191)->default('-0.15');
            $table->string('s_1', 191)->default('1');
            $table->string('s_2', 191)->default('0.8');
            $table->string('s_3', 191)->default('0.65');
            $table->string('s_4', 191)->default('-0.15');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scorings');
    }
};