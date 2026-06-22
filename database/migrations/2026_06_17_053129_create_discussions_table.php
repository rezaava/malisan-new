<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('session_id');
            $table->longText('text');
            $table->integer('counter')->default(0);
            $table->integer('status')->nullable()->comment('1.ali 2.khob 3.motevaset 4.bad');
            $table->string('comment1', 191)->nullable();
            $table->integer('score2')->default(0);
            $table->integer('score3')->default(0);
            $table->text('comment2')->nullable();
            $table->text('comment3')->nullable();
            $table->integer('is_edit')->default(0);
            $table->integer('score')->default(0);
            $table->enum('level', ['1', '2']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discussions');
    }
};