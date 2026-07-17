<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('angizeshes', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->tinyInteger('level')->comment('1=20, 2=18-20, 3=15-18, 4=12-15, 5=10-12, 6=<10, 7=ورود');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('angizeshes');
    }
};