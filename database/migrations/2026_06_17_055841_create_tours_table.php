<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->integer('active')->default(0);
            $table->mediumText('title')->nullable();
            $table->mediumText('image')->nullable();
            $table->mediumText('title_hint')->nullable();
            $table->mediumText('abs_hint')->nullable();
            $table->mediumText('keyword_hint')->nullable();
            $table->mediumText('file_hint')->nullable();
            $table->mediumText('sponser_name')->nullable();
            $table->mediumText('sponser_image')->nullable();
            $table->mediumText('donate')->nullable();
            $table->integer('title_min')->nullable();
            $table->integer('title_max')->nullable();
            $table->integer('abs_min')->nullable();
            $table->integer('abs_max')->nullable();
            $table->integer('key_min')->nullable();
            $table->integer('key_max')->nullable();
            $table->integer('keyword_min')->nullable();
            $table->integer('keyword_max')->nullable();
            $table->integer('file_max')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};