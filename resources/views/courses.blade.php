@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-courses.css')}}">
@endsection

@section('mohtava')
<div class="content-header">
    <div class="header-left">
        <button class="archive-btn">
            <i class="fas fa-archive"></i>
            <span>آرشیوها</span>
        </button>
        <button class="courses-btn active">
            <i class="fas fa-book"></i>
            <span>درس‌ها</span>
        </button>
    </div>
    <a href="ُ#" class="new-course-btn">
        <i class="fas fa-plus-circle"></i>
        <span>درس جدید</span>
    </a>
</div>

<div class="courses-grid">
    <a href="{{Route('course')}}" class="course-card">
        <div class="course-image">
            <img src="test.jpg" alt="درس فیزیک">
            <div class="course-badge">فعال</div>
        </div>
        <div class="course-actions">
            <div class="action-item" data-action="حذف" onclick="event.preventDefault(); event.stopPropagation();">
                <i class="fas fa-trash-alt"></i>
                <span class="action-tooltip">حذف</span>
            </div>
            <div class="action-item" data-action="ویرایش" onclick="event.preventDefault(); event.stopPropagation();">
                <i class="fas fa-edit"></i>
                <span class="action-tooltip">ویرایش</span>
            </div>
            <div class="action-item" data-action="اشتراک گذاری" onclick="event.preventDefault(); event.stopPropagation();">
                <i class="fas fa-share-alt"></i>
                <span class="action-tooltip">اشتراک گذاری</span>
            </div>
            <div class="action-item" data-action="کپی" onclick="event.preventDefault(); event.stopPropagation();">
                <i class="fas fa-copy"></i>
                <span class="action-tooltip">کپی</span>
            </div>
            <div class="action-item" data-action="آرشیو" onclick="event.preventDefault(); event.stopPropagation();">
                <i class="fas fa-archive"></i>
                <span class="action-tooltip">آرشیو</span>
            </div>
            <div class="action-item" data-action="فعال/غیرفعال" onclick="event.preventDefault(); event.stopPropagation();">
                <i class="fas fa-toggle-on"></i>
                <span class="action-tooltip">فعال/غیرفعال</span>
            </div>
        </div>
        <div class="course-info">
            <h3 class="course-title">فیزیک عمومی</h3>
            <p class="course-code">PHYS101</p>
        </div>
    </a>

    <a href="{{Route('course')}}" class="course-card">
        <div class="course-image">
            <img src="test.jpg" alt="درس برنامه‌نویسی">
            <div class="course-badge">فعال</div>
        </div>
        <div class="course-actions">
            <div class="action-item" data-action="حذف" onclick="event.preventDefault(); event.stopPropagation();">
                <i class="fas fa-trash-alt"></i>
                <span class="action-tooltip">حذف</span>
            </div>
            <div class="action-item" data-action="ویرایش" onclick="event.preventDefault(); event.stopPropagation();">
                <i class="fas fa-edit"></i>
                <span class="action-tooltip">ویرایش</span>
            </div>
            <div class="action-item" data-action="اشتراک گذاری" onclick="event.preventDefault(); event.stopPropagation();">
                <i class="fas fa-share-alt"></i>
                <span class="action-tooltip">اشتراک گذاری</span>
            </div>
            <div class="action-item" data-action="کپی" onclick="event.preventDefault(); event.stopPropagation();">
                <i class="fas fa-copy"></i>
                <span class="action-tooltip">کپی</span>
            </div>
            <div class="action-item" data-action="آرشیو" onclick="event.preventDefault(); event.stopPropagation();">
                <i class="fas fa-archive"></i>
                <span class="action-tooltip">آرشیو</span>
            </div>
            <div class="action-item" data-action="فعال/غیرفعال" onclick="event.preventDefault(); event.stopPropagation();">
                <i class="fas fa-toggle-on"></i>
                <span class="action-tooltip">فعال/غیرفعال</span>
            </div>
        </div>
        <div class="course-info">
            <h3 class="course-title">برنامه‌نویسی وب</h3>
            <p class="course-code">WEB101</p>
        </div>
    </a>
</div>
@endsection