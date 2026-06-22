<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('role')->default(1);
            $table->string('name', 191)->nullable();
            $table->string('family', 191)->nullable();
            $table->tinyInteger('gender')->nullable()->comment('0 female 1 male');
            $table->string('email', 191)->nullable();
            $table->string('national', 191)->nullable();
            $table->string('shenasname', 191)->nullable();
            $table->string('personal', 191)->nullable();
            $table->string('birthdate', 191)->nullable();
            $table->string('city_birth', 191)->nullable();
            $table->string('city', 191)->nullable();
            $table->string('address', 191)->nullable();
            $table->string('postal', 191)->nullable();
            $table->string('tel', 191)->nullable();
            $table->string('mobile', 191)->nullable();
            $table->string('sms', 5)->nullable();
            $table->integer('active')->default(0);
            $table->integer('tour')->default(0);
            $table->string('tel_work', 191)->nullable();
            $table->string('uni_email', 191)->nullable();
            $table->string('web', 191)->nullable();
            $table->string('scholar', 191)->nullable();
            $table->string('social', 191)->nullable();
            $table->string('degree', 191)->nullable();
            $table->string('field', 191)->nullable()->comment('reshte');
            $table->string('trend', 191)->nullable()->comment('gerayesh');
            $table->string('trend_en', 191)->nullable()->comment('gerayesh');
            $table->string('research', 191)->nullable()->comment('pajohesh');
            $table->string('image', 191)->nullable();
            $table->string('shaba', 191)->nullable();
            $table->string('turn', 191)->nullable()->comment('dore');
            $table->string('password', 191);
            $table->text('aneto_token')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};