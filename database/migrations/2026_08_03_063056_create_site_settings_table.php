<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enable_teacher_survey')->default(1)->comment('فعال‌سازی نظرسنجی از اساتید: 1=فعال، 0=غیرفعال');
            $table->boolean('enable_student_survey')->default(1)->comment('فعال‌سازی نظرسنجی از دانشجویان: 1=فعال، 0=غیرفعال');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
