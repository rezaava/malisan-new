<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            // نظرسنجی‌ها (قبلی)
            $table->boolean('enable_teacher_survey')->default(1)->comment('فعال‌سازی نظرسنجی از اساتید');
            $table->boolean('enable_student_survey')->default(1)->comment('فعال‌سازی نظرسنجی از دانشجویان');

            // ---------- بارم‌بندی ----------
            $table->decimal('mostamar_nomre', 5, 2)->default(12);
            $table->decimal('taklif_seminar_nomre', 5, 2)->default(0);
            $table->decimal('azmon_nomre', 5, 2)->default(0);
            $table->decimal('hozor_ghayab_nomre', 5, 2)->default(0);
            $table->decimal('miyan_term_nomre', 5, 2)->default(0);
            $table->decimal('kar_amali_nomre', 5, 2)->default(0);
            $table->decimal('payan_term_nomre', 5, 2)->default(8);

            // ---------- فعالیت‌ها ----------
            $table->boolean('soal_last')->default(1);
            $table->boolean('gozaresh_last')->default(1);
            $table->boolean('taklif_last')->default(1);
            $table->unsignedInteger('jalasat')->default(16);
            $table->unsignedInteger('max_taklif')->default(3);
            $table->unsignedInteger('max_soal')->default(3);
            $table->unsignedInteger('daily_judgment_limit')->default(5);
            $table->text('tarahi_soal_desc')->nullable();
            $table->text('ersal_gozaresh_desc')->nullable();

            // ---------- خودآزمایی ----------
            $table->unsignedInteger('min_w_khod')->default(14);
            $table->unsignedInteger('q_num')->default(10);
            $table->tinyInteger('sath_khod')->default(1); // 1=عالی، 2=عالی و خوب، 3=خوب
            $table->boolean('natije')->default(1);
            $table->boolean('show_quiz')->default(1);
            $table->boolean('time_limit_khod')->default(0);
            $table->enum('time_type', ['per_question', 'total'])->default('per_question');
            $table->unsignedInteger('time_per_question')->default(45)->nullable();
            $table->unsignedInteger('total_time_limit')->nullable();

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