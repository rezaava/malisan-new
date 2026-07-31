<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->integer('jalasat')->default(16);
            
            // فیلدهای جدید برای بارم‌بندی
            $table->integer('hozor_ghayab_nomre')->default(0); // حضور و غیاب
            $table->string('hozor_ghayab_desc', 191)->nullable()->default('نمره حضور و غیاب');
            
            $table->integer('miyan_term_nomre')->default(0); // میان ترم
            $table->string('miyan_term_desc', 191)->nullable()->default('نمره میان ترم');
            
            $table->integer('kar_amali_nomre')->default(0); // کار عملی (بازدید|آزمایشگاه|کارگاه)
            $table->string('kar_amali_desc', 191)->nullable()->default('نمره کار عملی');
            
            $table->integer('payan_term_nomre')->default(6); // پایان ترم با نمره پیش‌فرض 6
            $table->string('payan_term_desc', 191)->nullable()->default('نمره پایان ترم');
            
            // فیلدهای قدیمی با متن‌های جدید
            $table->integer('tarahi_soal_nomre')->nullable()->default(10);
            $table->string('tarahi_soal_desc', 191)->nullable()->default('یک سؤال خلاقانه طراحی کنید که به یادگیری دوستانتان کمک کند و به نام خودتان منتشر شود. قبل از ارسال، حتماً سؤالاتی که دیگران طرح کرده اند را مرور کنید تا از تکراری نبودن سوال خود مطمئن شوید.');
            
            $table->integer('ersal_gozaresh_nomre')->nullable()->default(10);
            $table->string('ersal_gozaresh_desc', 191)->nullable()->default('موضوع اصلی این جلسه چه بود و چه هدفی داشت؟ لطفاً یک نکتهٔ آموزنده از مطالب ارائه شده را با بیانی دیگر (به زبان خودتان) بازنویسی کنید.');
            
            $table->integer('taklif_seminar_nomre')->nullable()->default(0);
            $table->string('taklif_seminar_desc', 191)->nullable();
            $table->tinyInteger('taklif_seminar_type')->nullable()->default(1)->comment('1.taklif 2.seminar');
            $table->integer('quiz_mid_nomre')->nullable()->default(0);
            $table->string('quiz_mid_desc', 191)->nullable()->default('1');
            $table->tinyInteger('quiz_mid_type')->nullable()->default(1)->comment('1.quiz 2.mid');
            $table->integer('pishraft_nomre')->nullable()->default(40);
            $table->string('pishraft_desc', 191)->nullable()->default('امتیاز این قسمت توسط سیستم محاسبه و به دانشجو نمایش می دهد');
            $table->integer('talash_nomre')->nullable()->default(40);
            $table->string('talash_desc', 191)->nullable()->default('امتیاز این قسمت توسط سیستم محاسبه و به دانشجو نمایش می دهد');
            $table->integer('hozor_nomre')->nullable()->default(0);
            $table->string('hozor_desc', 191)->nullable();
            $table->integer('amali_nomre')->nullable()->default(0);
            $table->string('amali_desc', 191)->nullable()->default('امتیاز این بخش در فعالیت مستمر دانشجو محاسبه نمی شود و توسط استاد در پایان ترم داده می شود.');
            $table->integer('final_nomre')->nullable()->default(30);
            $table->string('final_desc', 191)->nullable()->default('امتیاز این بخش در فعالیت مستمر دانشجو محاسبه نمی شود و توسط استاد در پایان ترم داده می شود.');
            $table->integer('erfagh_nomre')->nullable()->default(0);
            $table->string('erfagh_desc', 191)->nullable();
            $table->tinyInteger('soal_last')->default(1);
            $table->tinyInteger('gozaresh_last')->default(1);
            $table->integer('mostamar_nomre')->default(12);
            $table->tinyInteger('taklif_last')->default(1);
            $table->integer('max_soal')->nullable()->default(3);
            $table->integer('min_soal')->nullable()->default(2);
            $table->integer('min_davari')->default(14);
            $table->integer('max_taklif')->default(3);
            $table->integer('max_seminar')->nullable()->default(4);
            $table->integer('max_gozaresh')->nullable()->default(4);
            $table->integer('max_gheibat')->nullable()->default(3);
            $table->integer('min_w_khod')->nullable()->default(14);
            $table->integer('q_num')->nullable()->default(10);
            $table->integer('sath_khod')->nullable()->default(2);
            $table->integer('show_khod')->nullable()->default(1);
            $table->integer('quiz_num')->nullable()->default(1);
            $table->integer('sath_quiz')->nullable()->default(3);
            $table->integer('natije')->nullable()->default(1);
            $table->integer('show_quiz')->nullable()->default(1);
            $table->integer('azmon_nomre')->default(0);
            $table->boolean('time_limit_khod')->default(0);
            $table->integer('time_per_question')->default(45);
            $table->integer('total_time_limit')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};