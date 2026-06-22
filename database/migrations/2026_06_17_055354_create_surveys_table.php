<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('cat_id')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('group')->nullable()->default(0)->comment('0.all else course_id');
            $table->text('text');
            $table->tinyInteger('type')->comment('1.text 2.radio 3.check');
            $table->integer('desc_add')->nullable();
            $table->tinyInteger('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};