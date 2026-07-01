<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('score_exercises', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->comment('کاربری که داوری کرده');
            $table->bigInteger('exercise_answer_id')->comment('آی‌دی پاسخ تکلیف');
            
            // نمره (هر داور فقط یک نمره می‌دهد)
            $table->tinyInteger('score')->nullable()->comment('نمره (۱=عالی،۲=خوب،۳=متوسط)');
            
            // فیلدهای ایراد (۰=ندارد، ۱=دارد)
            $table->tinyInteger('negaresh')->default(0)->comment('ایراد نگارشی');
            $table->tinyInteger('gozine')->default(0)->comment('ایراد گزینه‌ها');
            $table->tinyInteger('dark')->default(0)->comment('ایراد گویایی');
            
            // توضیحات داور
            $table->text('comment')->nullable()->comment('توضیح داور');
            
            // وضعیت داوری این رکورد
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned'])->default('pending');
            
            $table->timestamps();
            
            // ایندکس‌ها
            $table->index('user_id');
            $table->index('exercise_answer_id');
            $table->index('status');
            
            // ترکیب یکتا برای جلوگیری از داوری تکراری
            $table->unique(['user_id', 'exercise_answer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('score_exercises');
    }
};