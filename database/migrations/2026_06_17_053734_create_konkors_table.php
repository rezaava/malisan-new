<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konkors', function (Blueprint $table) {
            $table->id();
            $table->integer('active')->default(0);
            $table->string('reshte', 191);
            $table->string('gerayesh', 191);
            $table->string('dars', 191);
            $table->timestamps();
            $table->datetime('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konkors');
    }
};