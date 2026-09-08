<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluation_activity_limits', function (Blueprint $table) {
            $table->id();
            $table->string('activity');
            $table->unsignedInteger('max_score')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_activity_limits');
    }
};