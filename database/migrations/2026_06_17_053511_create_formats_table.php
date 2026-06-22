<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formats', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('mime_type');
            $table->text('extension');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formats');
    }
};