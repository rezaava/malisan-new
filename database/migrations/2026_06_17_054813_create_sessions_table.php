<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_id');
            $table->longText('text')->nullable();
            $table->string('file', 191)->nullable();
            $table->text('link')->nullable();
            $table->text('majazi')->nullable();
            $table->longText('aparat')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->integer('number');
            $table->string('name', 191)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};