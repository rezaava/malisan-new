<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('azmons', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->string('code', 191);
            $table->string('title', 191);
            $table->float('zarib')->nullable();
            $table->text('description')->nullable();
            $table->integer('num');
            $table->integer('sath');
            $table->string('sessions', 191);
            $table->integer('show_nomre')->default(0);
            $table->integer('time')->nullable();
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->integer('show_ans')->default(0);
            $table->integer('show_state')->default(0);
            $table->integer('changeable')->default(0);
            $table->integer('show_remain')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('azmons');
    }
};