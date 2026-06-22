<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('sub_id')->nullable();
            $table->integer('type')->comment('1.question 2.disc');
            $table->integer('score1')->comment('1.type 2.gozine beham rikhte 3.ok 4.irad darad');
            $table->integer('score2');
            $table->integer('score3');
            $table->text('comment2')->nullable();
            $table->text('comment3')->nullable();
            $table->text('comment1')->nullable();
            $table->mediumText('description')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};