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

            $table->unsignedInteger('judging_quality')->default(15);
            $table->unsignedInteger('self_test_quality')->default(25);
            $table->unsignedInteger('report_quality')->default(15);
            $table->unsignedInteger('question_quality')->default(15);
            $table->unsignedInteger('self_test_participation')->default(9);
            $table->unsignedInteger('judging_completion')->default(8);
            $table->unsignedInteger('report_submission')->default(5);
            $table->unsignedInteger('question_creation')->default(8);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_activity_limits');
    }
};