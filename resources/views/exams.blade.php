@extends('layout.master')

@section('title')
ملیسان | آزمون‌ها
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-exams.css')}}">
@endsection

@section('mohtava')
<div class="exams-header">
    <div class="exams-title">
        <h2>آزمون‌های پیش رو</h2>
        <p>آماده شوید برای موفقیت در آزمون‌های پیش‌رو</p>
    </div>
</div>

<div class="exams-grid">
    <a href="/exams/1" class="exam-card" style="background-image: url('test.jpg');">
        <div class="exam-card-overlay">
            <h3 class="exam-title">آزمون ریاضیات پایه</h3>
            <p class="exam-desc">آزمون جامع ریاضیات پایه شامل مباحث مثلثات، حد و مشتق</p>
            <span class="exam-enter-btn">ورود به آزمون</span>
        </div>
    </a>

    <a href="/exams/2" class="exam-card" style="background-image: url('test.jpg');">
        <div class="exam-card-overlay">
            <h3 class="exam-title">آزمون فیزیک عمومی</h3>
            <p class="exam-desc">آزمون جامع فیزیک عمومی شامل مباحث مکانیک، گرما و نور</p>
            <span class="exam-enter-btn">ورود به آزمون</span>
        </div>
    </a>

    <a href="/exams/3" class="exam-card" style="background-image: url('test.jpg');">
        <div class="exam-card-overlay">
            <h3 class="exam-title">آزمون برنامه‌نویسی</h3>
            <p class="exam-desc">آزمون جامع برنامه‌نویسی شامل مباحث پایتون، الگوریتم و دیتابیس</p>
            <span class="exam-enter-btn">ورود به آزمون</span>
        </div>
    </a>

    <a href="/exams/4" class="exam-card" style="background-image: url('test.jpg');">
        <div class="exam-card-overlay">
            <h3 class="exam-title">آزمون زبان انگلیسی</h3>
            <p class="exam-desc">آزمون جامع زبان انگلیسی شامل مباحث گرامر، مکالمه و خواندن</p>
            <span class="exam-enter-btn">ورود به آزمون</span>
        </div>
    </a>
</div>
@endsection