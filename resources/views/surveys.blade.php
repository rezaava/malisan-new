@extends('layout.master')

@section('title')
ملیسان | نظرسنجی‌ها
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-surveys.css')}}">
@endsection

@section('mohtava')
<div class="surveys-header">
    <div class="surveys-title">
        <h2>نظرسنجی‌ها</h2>
        <p>نظر خود را درباره دوره‌ها و اساتید با ما به اشتراک بگذارید</p>
    </div>
</div>

<div class="surveys-grid">
    <div class="survey-card" style="background-image: url('test.jpg');">
        <div class="survey-card-overlay">
            <div class="survey-icon">
                <i class="fas fa-paper-plane"></i>
                <span class="survey-type">مجازی</span>
            </div>
            <h3 class="survey-title">ارزیابی کیفیت تدریس</h3>
            <p class="survey-code">کد درس: MATH101</p>
            <p class="survey-max">حداکثر شرکت‌کنندگان: ۵۰ نفر</p>
            <a href="/surveys/1" class="survey-enter-btn">نظرسنجی</a>
        </div>
    </div>

    <div class="survey-card" style="background-image: url('test.jpg');">
        <div class="survey-card-overlay">
            <div class="survey-icon">
                <i class="fas fa-paper-plane"></i>
                <span class="survey-type">مجازی</span>
            </div>
            <h3 class="survey-title">نظرسنجی محتوای دوره</h3>
            <p class="survey-code">کد درس: PHYS101</p>
            <p class="survey-max">حداکثر شرکت‌کنندگان: ۳۵ نفر</p>
            <a href="/surveys/2" class="survey-enter-btn">نظرسنجی</a>
        </div>
    </div>

    <div class="survey-card" style="background-image: url('test.jpg');">
        <div class="survey-card-overlay">
            <div class="survey-icon">
                <i class="fas fa-paper-plane"></i>
                <span class="survey-type">مجازی</span>
            </div>
            <h3 class="survey-title">ارزیابی استاد درس</h3>
            <p class="survey-code">کد درس: WEB101</p>
            <p class="survey-max">حداکثر شرکت‌کنندگان: ۴۰ نفر</p>
            <a href="/surveys/3" class="survey-enter-btn">نظرسنجی</a>
        </div>
    </div>

    <div class="survey-card" style="background-image: url('test.jpg');">
        <div class="survey-card-overlay">
            <div class="survey-icon">
                <i class="fas fa-paper-plane"></i>
                <span class="survey-type">مجازی</span>
            </div>
            <h3 class="survey-title">نظرسنجی زمان برگزاری</h3>
            <p class="survey-code">کد درس: DB101</p>
            <p class="survey-max">حداکثر شرکت‌کنندگان: ۳۰ نفر</p>
            <a href="/surveys/4" class="survey-enter-btn">نظرسنجی</a>
        </div>
    </div>
</div>
@endsection