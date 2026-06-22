@extends('layout.master')

@section('title')
ملیسان | دوره‌های ملیسان
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-publics.css')}}">
@endsection

@section('mohtava')
<div class="content-header">
    <a href="/courses/new" class="add-course-btn" title="افزودن دوره جدید">
        <i class="fas fa-plus-circle"></i>
        <span class="add-tooltip">دوره جدید</span>
    </a>
</div>

<div class="courses-grid">
    <div class="course-card-wrapper">
        <a href="/malisan-courses/1" class="course-card">
            <div class="course-image" style="background-image: url('test.jpg');"></div>
            <div class="course-info">
                <h3 class="course-title">برنامه‌نویسی پیشرفته</h3>
                <span class="view-btn">مشاهده</span>
            </div>
        </a>
    </div>

    <div class="course-card-wrapper">
        <a href="/malisan-courses/2" class="course-card">
            <div class="course-image" style="background-image: url('test.jpg');"></div>
            <div class="course-info">
                <h3 class="course-title">مدیریت پروژه</h3>
                <span class="view-btn">مشاهده</span>
            </div>
        </a>
    </div>

    <div class="course-card-wrapper">
        <a href="/malisan-courses/3" class="course-card">
            <div class="course-image" style="background-image: url('test.jpg');"></div>
            <div class="course-info">
                <h3 class="course-title">طراحی تجربه کاربری</h3>
                <span class="view-btn">مشاهده</span>
            </div>
        </a>
    </div>

    <div class="course-card-wrapper">
        <a href="/malisan-courses/4" class="course-card">
            <div class="course-image" style="background-image: url('test.jpg');"></div>
            <div class="course-info">
                <h3 class="course-title">زبان انگلیسی تخصصی</h3>
                <span class="view-btn">مشاهده</span>
            </div>
        </a>
    </div>

    <div class="course-card-wrapper">
        <a href="/malisan-courses/5" class="course-card">
            <div class="course-image" style="background-image: url('test.jpg');"></div>
            <div class="course-info">
                <h3 class="course-title">هوش مصنوعی</h3>
                <span class="view-btn">مشاهده</span>
            </div>
        </a>
    </div>

    <div class="course-card-wrapper">
        <a href="/malisan-courses/6" class="course-card">
            <div class="course-image" style="background-image: url('test.jpg');"></div>
            <div class="course-info">
                <h3 class="course-title">امنیت سایبری</h3>
                <span class="view-btn">مشاهده</span>
            </div>
        </a>
    </div>

    <div class="course-card-wrapper">
        <a href="/malisan-courses/7" class="course-card">
            <div class="course-image" style="background-image: url('test.jpg');"></div>
            <div class="course-info">
                <h3 class="course-title">تحلیل داده</h3>
                <span class="view-btn">مشاهده</span>
            </div>
        </a>
    </div>

    <div class="course-card-wrapper">
        <a href="/malisan-courses/8" class="course-card">
            <div class="course-image" style="background-image: url('test.jpg');"></div>
            <div class="course-info">
                <h3 class="course-title">بازاریابی دیجیتال</h3>
                <span class="view-btn">مشاهده</span>
            </div>
        </a>
    </div>
</div>
@endsection