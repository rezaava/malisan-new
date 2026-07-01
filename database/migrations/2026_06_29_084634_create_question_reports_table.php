<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->comment('کاربری که گزارش داده');
            $table->bigInteger('question_id')->comment('آی‌دی سوال');
            $table->text('description')->comment('توضیح ایراد');
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'rejected'])->default('pending')->comment('وضعیت گزارش');
            $table->text('admin_response')->nullable()->comment('پاسخ مدیر');
            $table->bigInteger('reviewed_by')->nullable()->comment('کاربری که بررسی کرده');
            $table->timestamp('reviewed_at')->nullable()->comment('زمان بررسی');
            $table->timestamps();
            
            // ایندکس‌ها
            $table->index('user_id');
            $table->index('question_id');
            $table->index('status');
            
            // جلوگیری از گزارش تکراری برای یک سوال توسط یک کاربر
            $table->unique(['user_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_reports');
    }
};